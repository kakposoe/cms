<?php

namespace Tendoo\Core\Http\Controllers\Dashboard;

use Illuminate\Http\Request;
use Tendoo\Core\Http\Controllers\DashboardController;
use Tendoo\Core\Http\Requests\CrudPostRequest;
use Tendoo\Core\Http\Requests\CrudPutRequest;
use Tendoo\Core\Facades\Hook;
use Illuminate\Support\Facades\Event;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Tendoo\Core\Exceptions\CrudException;

class CrudController extends DashboardController
{
    public function __construct()
    {
        parent::__construct();
        $this->middleware( function( $request, $next ) {
            return $next( $request );
        });
    }

    /**
     * CRUD delete we expect this request to be 
     * provided by an Ajax Request
     * @param void
     * @return view
     */
    public function crudDelete( $namespace, $id )
    {
        /**
         * Catch event before deleting user
         */
        $crudClass      =   Hook::filter( 'register.crud', $namespace, $id );
        $resource       =   new $crudClass;

        if ( empty( $resource ) ) {
            return response([
                'status'    =>  'danger',
                'message'   =>  __( 'The crud resource is either not handled by the system or by any installed module.' )
            ], 403 );
        }

        /**
         * Run the filter before deleting
         */
        if ( method_exists( $resource, 'beforeDelete' ) ) {

            /**
             * the callback should return an empty value to proceed.
             */
            if( ! empty( $response = $resource->beforeDelete( $namespace, $id ) ) ) {
                return $response;
            }
        }

        /**
         * We'll retreive the model and delete it
         */
        $model          =   $resource->get( 'model' );
        $model::find( $id )->delete();

        Event::fire( 'after.deleting.crud', $namespace, $id );

        return [
            'status'    =>  'success',
            'message'   =>  __( 'The entry has been successfully delete.' )
        ];
    }

    /**
     * Dashboard Crud POST
     * receive and treat POST request for CRUD Resource
     * @param void
     * @return void
     */
    public function crudPost( String $namespace, CrudPostRequest $request )
    {
        $crudClass          =   Hook::filter( 'register.crud', $namespace );

        /**
         * In case nothing handle this crud
         */
        if ( ! class_exists( $crudClass ) ) {
            return redirect()->route( 'errors', [ 'code' => 'unhandled-crud-resource' ]);
        }
        
        $resource   =   new $crudClass;
        $model      =   $resource->getModel();
        $entry      =   new $model;

        /**
         * Filter POST input
         * check if on the CRUD resource the filter exists
         */
        $inputs         =   $request->all();
        if ( method_exists( $resource, 'filterPostInputs' ) ) {
            $inputs     =   $resource->filterPostInputs( $request->all() );

            /**
             * if a redirect response is returned
             * the execution should stop immediately
             */
            if ( $inputs instanceof RedirectResponse ) {
                return $inputs;
            }
        }

        foreach ( $inputs as $name => $value ) {

            /**
             * If submitted field are part of fillable fields
             */
            if ( in_array( $name, $resource->getFillable() ) ) {

                /**
                 * We might give the capacity to filter fields 
                 * before storing. This can be used to apply specific formating to the field.
                 */
                if ( method_exists( $resource, 'filterPost' ) ) {
                    $entry->$name   =   $resource->filterPost( $value, $name );
                } else {
                    $entry->$name   =   $value;
                }
            }
        }

        $entry->save();

        /**
         * Create an event after crud POST
         */
        if ( method_exists( $resource, 'afterPost' ) ) {
            $resource->afterPost( $entry );
        }

        /**
         * @todo adding a link to edit the new entry
         */
        return redirect()->route( $resource->getMainRoute() )->with([
            'status'    =>  'success',
            'message'   =>  __( 'A new entry has been successfully created.' )
        ]);

        // return [
        //     'status'    =>  'success',
        //     'message'   =>  __( 'A new entry has been successfully created' )
        // ];
    }

    /**
     * Dashboard CRUD PUT
     * receive and treat a PUT request for CRUD resource
     * @param string resource namespace
     * @param int primary key
     * @param object request : CrudPutRequest
     * @return void
     */
    public function crudPut( String $namespace, $entry, CrudPutRequest $request  ) 
    {
        $crudClass          =   Hook::filter( 'register.crud', $namespace );

        /**
         * In case nothing handle this crud
         */
        if ( ! class_exists( $crudClass ) ) {
            return redirect()->route( 'errors', [ 'code' => 'unhandled-crud-resource' ]);
        }
        
        $resource   =   new $crudClass;
        $model      =   $resource->getModel();
        $entry      =   $model::find( $entry );

        /**
         * Filter PUT input
         * check if on the CRUD resource the filter exists
         */
        $inputs         =   $request->all();
        if ( method_exists( $resource, 'filterPutInputs' ) ) {
            $inputs     =   $resource->filterPutInputs( $request->all(), $entry );

            /**
             * if a redirect response is returned
             * the execution should stop immediately
             */
            if ( $inputs instanceof RedirectResponse ) {
                return $inputs;
            }
        }

        foreach ( $inputs as $name => $value ) {

            /**
             * If submitted field are part of fillable fields
             * The field should not be null
             */
            if ( in_array( $name, $resource->getFillable() ) && $value !== null ) {

                /**
                 * We might give the capacity to filter fields 
                 * before storing. This can be used to apply specific formating to the field.
                 */
                if ( method_exists( $resource, 'filterPut' ) ) {
                    $entry->$name   =   $resource->filterPut( $value, $name );
                } else {
                    $entry->$name   =   $value;
                }
            }
        }

        $entry->save();

        /**
         * Create an event after crud put
         */
        if ( method_exists( $resource, 'afterPut' ) ) {
            $resource->afterPut( $entry );
        }

        /**
         * @todo adding a link to edit the new entry
         */
        return redirect()->route( $resource->getMainRoute() )->with([
            'status'    =>  'success',
            'message'   =>  __( 'An new entry has been successfully updated.' )
        ]);
        // return [
        //     'status'    =>  'success',
        //     'message'   =>  __( 'An new entry has been successfully updated.' )
        // ];
    }

    /**
     * Crud List
     * @return array of results
     */
    public function crudList( string $namespace )
    {
        $crudClass          =   Hook::filter( 'register.crud', $namespace );
        $service            =   app()->make( 'Tendoo\Core\Services\Crud' );

        /**
         * In case nothing handle this crud
         */
        if ( ! class_exists( $crudClass ) ) {
            throw new CrudException([
                'message'   =>  sprintf( __( 'Unable to load the CRUD resource : %s.' ), $crudClass )
            ]);
        }

        $resource   =   new $crudClass;

        return $resource->getEntries();
    }

    /**
     * CRUD Bulk Action
     * @param string namespace
     * @return void
     */
    public function crudBulkActions( String $namespace, Request $request )
    {
        $crudClass          =   Hook::filter( 'register.crud', $namespace );

        /**
         * In case nothing handle this crud
         */
        if ( ! class_exists( $crudClass ) ) {
            return redirect()->route( 'errors', [ 'code' => 'unhandled-crud-resource' ]);
        }
        
        $resource   =   new $crudClass;
        
        /**
         * Check if an entry is selected, 
         * else throw an error
         */
        if ( $request->input( 'entry_id' ) == null ) {
            // return redirect()->route( $resource->getMainRoute() )->with();
            return [
                'status'    =>  'danger',
                'message'   =>  __( 'You must select an entry.' )
            ];
        }

        if ( $request->input( 'action' ) == null ) {
            return [
                'status'    =>  'danger',
                'message'   =>  __( 'You must select an action to perform.' )
            ];
        }

        $response           =   $resource->bulkDelete( $request );
        $errors             =   [];

        if ( $response[ 'success' ] > 0 ) {
            $errors[ 'success' ]    =   sprintf( $resource->bulkDeleteSuccessMessage, $response[ 'success' ]);
        } 
        
        if ( $response[ 'danger' ] > 0 ) {
            $errors[ 'danger' ]     =   sprintf( $resource->bulkDeleteDangerMessage, $response[ 'danger' ]);
        }

        if ( $request->expectsJson() ) {
            return $errors;
        }
        return redirect()->route( $resource->getMainRoute() )->with( $errors );
    }

    /**
     * Crud GET
     * @param string resource namespace
     * @return CRUD Response
     */
    public function crudGet( string $namespace, Request $request )
    {
        $crudClass          =   Hook::filter( 'register.crud', $namespace );

        /**
         * Let's check it the resource has a method to retreive an item
         */
        $resource  =   new $crudClass;

        if ( method_exists( $resource, 'getEntries' ) ) {
            return $resource->getEntries( $request );
        } else {
            throw new CrudException([
                'message'   =>  __( 'Unable to retreive items. The current CRUD resource doesn\'t implement "getEntries" methods' ),
                'status'    =>  'danger'
            ]);
        }
    }
}
