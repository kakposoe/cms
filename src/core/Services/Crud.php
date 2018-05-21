<?php
namespace Tendoo\Core\Services;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;
use Tendoo\Core\Facades\Hook;

class Crud 
{
    /**
     * Toggle feature array
     * @param boolean
     */
    protected $features       =   [
        'bulk-actions'      =>  true, // enable bulk action
        'single-action'     =>  true, // enable single action
        'checkboxes'        =>  true // enable checkboxes
    ];


    /**
     * Actions Array
     */
    protected $actions  =   [];

    /**
     * Protected columns
     */
    protected   $columns    =   [];

    /**
     * Link
     * @return array
     */
    protected   $links       =   [
        'list'      =>  [],
        'edit'      =>  [],
        'create'    =>  []
    ];

    /**
     * Define a where for getEntries
     * @var array
     */
    protected $listWhere    =   [];

    /**
     * Bulk Options
     * @param array
     */
    protected $bulkActions  =   [];

    /**
     * Construct Parent
     */
    public function __construct()
    {
        /**
         * @todo provide more build in actions
         */
        $this->bulkActions  =   [
            'delete_selected'   =>  __( 'Delete Selected entries' )
        ];

        /**
         * Bulk action messages
         */
        $this->bulkDeleteSuccessMessage     =   __( '%s entries has been deleted' );
        $this->bulkDeleteDangerMessage      =   __( '%s entries has not been deleted' );
    }

    /**
     * Is enabled
     * Return whether a feature is enabled (true) or not (false)
     * @param string feature name
     * @return boolean/null
     */
    public function isEnabled( $feature )
    {
        return @$this->features[ $feature ];
    }

    /**
     * Get namespace
     * @return string current namespace
     */
    public function getNamespace()
    {
        return $this->namespace;
    }


    /**
     * Get Bulk Actions
     * @return array of bulk actions
     */
    public function getBulkActions()
    {
        return $this->bulkActions;
    }

    /**
     * get Entries
     * @param crud config
     * @return entries
     */
    public function getEntries()
    {
        $request            =   app()->make( Request::class );
        $query              =   DB::table( $this->table );
        $columnsLongName    =   [];
        /**
         * Let's loop relation if they exists
         */
        if ( $this->relations ) {
            /**
             * First loop to retreive the columns and rename it
             */
            $select         =   [];

            /**
             * Building Select field for primary table
             * We're caching the table columns, since we would like to 
             * avoid many DB Calls
             */
            if( ! empty( Cache::get( 'table-columns-' . $this->table ) ) ) {
                $columns        =   Cache::get( 'table-columns-' . $this->table );
            } else {
                $columns        =   Schema::getColumnListing( $this->table );
                Cache::put( 'table-columns-' . $this->table, $columns, Carbon::now()->addDays(1) );
            }

            foreach( $columns as $index => $column ) {
                $__name             =   $this->table . '.' . $column;
                $columnsLongName[]  =   $__name;
                $select[]           =  $__name . ' as ' . $column;
            }

            /**
             * Build Select for joined table
             */
            
            foreach( $this->relations as $relation ) {
                /**
                 * We're caching the columns to avoid once again many DB request
                 */
                if( ! empty( Cache::get( 'table-columns-' . $relation[0] ) ) ) {
                    $columns        =   Cache::get( 'table-columns-' . $relation[0] );
                } else {
                    $columns    =   Schema::getColumnListing( $relation[0] );
                    Cache::put( 'table-columns-' . $relation[0], $columns, Carbon::now()->addDays(1) );
                }

                foreach( $columns as $index => $column ) {
                    $__name             =   $relation[0] . '.' . $column;
                    $columnsLongName[]  =   $__name;
                    $select[]           =   $__name . ' as ' . $relation[0] . '_' . $column;
                }
            }

            $query          =   call_user_func_array([ $query, 'select' ], $select );

            foreach( $this->relations as $relation ) {
                $query->join( $relation[0], $relation[1], $relation[2], $relation[3] );
            }

            /**
             * check if the query has a where statement
             */
            if ( $this->listWhere ) {
                foreach( $this->listWhere as $key => $value ) {
                    $query->where( $key, $value );
                }
            }
        }

        /**
         * Order the current result, according to the mentionned columns
         */
        if ( $request->query( 'descending' ) && $request->query( 'sortBy' ) ) {
            $query->orderBy( 
                $request->query( 'sortBy' ),
                $request->query( 'descending' ) == 'false' ? 'asc' : 'desc'
            );
        }

        /**
         * let's make the "perPage" value adjustable
         */
        $perPage    =   false;
        if ( $request->query( 'rowsPerPage' ) != '-1' ) {
            $perPage    =   $request->query( 'rowsPerPage' );
        }

        /**
         * searching
         */
        if ( $request->query( 'search' ) ) {
            foreach( $columnsLongName as $index => $column ) {
                if ( $index == 0 ) {
                    $query->where( $column, 'like', "%{$request->query( 'search' )}%" );
                } else {
                    $query->orWhere( $column, 'like', "%{$request->query( 'search' )}%" );
                }
            }
        }

        if ( $perPage == false ) {
            $perPage    =   $query->count();
        }

        $paginateResult     =   $query->paginate( $perPage );

        /**
         * if a filter is registered
         */
        if ( @$this->filterEntries ) {
            $paginateResult     =   $paginateResult->toArray();
            foreach( $paginateResult[ 'data' ] as $index => &$row ) {
                foreach( ( array ) $row as $column => $value ) {
                    if ( @$this->filterEntries[ $column ] != null ) {
                        $paginateResult[ 'data' ][ $index ]->$column =   $this->filterEntries[ $column ]( $value );
                    }
                }

                /**
                 * register action for each entries
                 * @hook crud.entry.actions
                 * @todo add it to the generator
                 */
                $row->{'$actions'}  =   Hook::filter( 'crud.entry.actions', [
                    'delete'    =>  [
                        'text'  =>  __( 'Delete' ),
                        'url'   =>  route( 'dashboard.crud.delete', [
                            'namespace' =>  $this->getNamespace(),
                            'id'        =>  $row->id
                        ]),
                        'ajax'      =>  true,
                        'confirm'   =>  true, // if the action require a confirmation
                        'confirmText'   =>  __( 'Would you like to delete this entry ?' ),
                        'method'    =>  'delete'
                    ],
                    'edit'    =>  [
                        'text'  =>  __( 'Edit' ),
                        'url'   =>  route( $this->editRoute, [
                            'entry'        =>  $row->id
                        ]),
                        'ajax'          =>  false
                    ]
                ], $row, $this->getNamespace() );
            }
        }

        return $paginateResult;
    }

    /**
     * Get action
     * @return array of actions
     */
    public function getActions()
    {
        return $this->actions;
    }

    /**
     * Get link
     * @return array of link
     */
    public function getLinks()
    {
        return $this->links;
    }

    /**
     * Get route
     * @return string 
     */
    public function getMainRoute()
    {
        return $this->mainRoute;
    }

    /**
     * Get Model
     * @return current model
     */
    public function getModel()
    {
        return $this->model;
    }

    /**
     * Get Fillable fields
     * @return array of string as field name
     */
    public function getFillable()
    {
        return $this->fillable;
    }
}