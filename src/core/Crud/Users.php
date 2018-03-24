<?php
namespace Tendoo\Core\Crud;
use Tendoo\Core\Services\Crud;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Tendoo\Core\Services\Field;
use Tendoo\Core\Services\Helper;
use Tendoo\Core\Models\User;
use Tendoo\Core\Facades\Hook;
use Tendoo\Core\Models\Option as OptionModel;

class Users extends Crud
{
    /**
     * define the base table
     */
    protected $table      =   'users';

    /**
     * base route name
     */
    protected $mainRoute      =   'dashboard.users.list';

    /**
     * Define namespace
     * @param string
     */
    protected $namespace  =   'system.users';

    /**
     * Model Used
     */
    protected $model      =   'Tendoo\Core\Models\User';

    /**
     * Adding relation
     */
    public $relations   =  [
        [ 'roles', 'users.role_id', '=', 'roles.id' ]
    ];

    /**
     * Fields which will be filled during post/put
     */
    public $fillable    =   [ 'username', 'email', 'password', 'role_id', 'active' ];

    /**
     * Define Constructor
     * @param 
     */
    public function __construct()
    {
        parent::__construct();

        $this->list_title           =   __( 'User List' );
        $this->list_description     =   __( 'List all users and roles' );  
        $this->edit_title           =   __( 'Edit a user' );
        $this->edit_description     =   __( 'Edit a user details.' );  
        $this->create_title         =   __( 'Create User' );
        $this->create_description   =   __( 'Create a new user.' );

        $this->setActions();
    }

    /**
     * Fields
     * @param object/null
     * @return array of field
     */
    public function getFields( $entry = null ) 
    {
        $fields     =   app()->make( Field::class );
        return $fields->createUserFields( $entry );
    }

    /**
     * Filter POST input fields
     * @param array of fields
     * @return array of fields
     */
    public function filterPostInputs( $inputs )
    {
        foreach( $inputs as $name => $value ) {
            if ( $name == 'password' ) {
                $inputs[ $name ]    =   bcrypt( $value );
            }
        }
        return $inputs;
    }

    /**
     * Filter PUT input fields
     * @param array of fields
     * @return array of fields
     */
    public function filterPutInputs( $inputs )
    {
        foreach( $inputs as $name => $value ) {
            if ( $name == 'password' ) {
                $inputs[ $name ]    =   bcrypt( $value );
            }
        }
        return $inputs;
    }

    /**
     * Validation Rule for POST/PUT request
     * @param object Request
     * @return void
     */
    public function validationRules( $request )
    {
        /**
         * Retreive the user if here provided
         */
        $user       =   $request->route( 'id' ) ? User::find( $request->route( 'id' ) ) : false;
        
        /**
         * If the current request process system.users namespace
         */
        $fields     =   $this->getFields( $user );

        if ( $request->route( 'namespace' ) == 'system.users' ) {

            /**
             * Use UserFieldsValidation and add assign it to "crud" validation array
             * the user object is send to perform validation and ignoring the current edited
             * user
             */
        }
        return Helper::getFieldsValidation( $fields );
    }

    /**
     * Before Delete
     * @return void
     */
    public function beforeDelete( $namespace, $id ) {
        if ( $namespace == 'system.users' ) {
            /**
             * @todo we might check if the 
             * user has the right to delete
             */
            if ( Auth::id() === ( int ) $id && Hook::filter( 'delete.user', true, $id ) ) {
                return response([
                    'status'    =>  'danger',
                    'message'   =>  __( 'You can\'t delete your own account' )
                ], 403 );
            }

            /**
             * Delete user options
             * when the request is allowed to delete
             * the users
             */
            $this->deleteOptions( $id );
        }
    }

    /**
     * get
     * @param string
     * @return mixed
     */
    public function get( $param )
    {
        switch( $param ) {
            case 'model' : return $this->model ; break;
        }
    }

    /**
     * Define Columns
     * @return array of columns configuration
     */
    public function getColumns() {
        return [
            'username'  =>  [
                'text'  =>  __( 'Username' )
            ],
            'email'  =>  [
                'text'  =>  __( 'Email' )
            ],
            'roles_name'    =>  [
                'text'      =>  __( 'Role' )
            ],
            'created_at'  =>  [
                'text'  =>  __( 'Member Since' )
            ],
            'active'    =>  [
                'text'  =>  __( 'Active' ),
                'filter'    =>  function( $value ) {
                    if ( ( int ) $value ) {
                        return __( 'Active' );
                    }
                    return __( 'Unactive' );
                }
            ]
        ];
    }

    /**
     * Define actions
     */
    public function setActions()
    {
        $this->actions      =   [
            'edit'      =>  function( $user ) {
                if ( Auth::id() == $user->id ) {
                    return [
                        'text'  =>  __( 'My Profile' ),
                        'url'   =>  url()->route( 'dashboard.users.profile.general' )
                    ];
                } else {
                    return [
                        'text'  =>  __( 'Edit' ),
                        'url'   =>  url()->route( 'dashboard.users.edit', [ 'id' => $user->id ] )
                    ];
                }
            },
            'delete'    =>  function( $user ) {
                if ( Auth::id() != $user->id ) {
                    return [
                        'type'  =>  'DELETE',
                            'url'   =>  url()->route( 'dashboard.crud.delete', [ 
                            'id'            =>  $user->id,
                            'namespace'     =>  'system.users'
                        ]),
                        'text'  =>  __( 'Delete' )
                    ];
                }
                return false;
            }
        ];
    }

    /**
     * Bulk Delete Action
     * @param object Request with object
     * @return false/array
     */
    public function bulkDelete( Request $request ) 
    {
        if ( $request->input( 'action' ) == 'delete_selected' ) {
            $status     =   [
                'success'   =>  0,
                'danger'    =>  0
            ];

            foreach ( $request->input( 'entry_id' ) as $id ) {
                if ( ( int ) $id == Auth::id() ) {
                    $status[ 'danger' ]++;
                } else {
                    /**
                     * This filter might block the
                     * deletion
                     */
                    if ( Hook::filter( 'delete.user', true, $id ) ) {
                        $this->model::find( $id )->delete();
                        $this->deleteOptions( $id );
                        $status[ 'success' ]++;
                    }
                }
            }
            return $status;
        }
        return false;
    }

    /**
     * get Links
     * @return array of links
     */
    public function getLinks()
    {
        return  [
            'list'  =>  [
                [ 'href'    =>  route( 'dashboard.users.create' ), 'text' => __( 'Add a user' ) ]
            ],
            'create'    =>  [
                [ 'href'    =>  route( 'dashboard.users.list' ), 'text' => __( 'Return' ), 'class' => 'btn btn-raised btn-secondary' ]
            ],
            'edit'      =>  [
                [ 'href'    =>  route( 'dashboard.users.list' ), 'text' => __( 'Return' ), 'class' => 'btn btn-raised btn-secondary' ]
            ]
        ];
    }

    /**
     * Delete the users Options
     * @param int user id
     * @return void
     */
    public function deleteOptions( $user_id )
    {
        OptionModel::where( 'user_id', $user_id )->delete();
    }
}