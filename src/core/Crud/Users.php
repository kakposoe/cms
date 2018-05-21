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
    protected $listRoute      =   'dashboard.users.list';

    /**
     * edit route
     * @todo add to the generator
     */
    protected $editRoute    =   'dashboard.users.edit';

    /**
     * create route
     * @todo add to the generator
     */
    protected $addRoute     =   'dashboard.users.create';

    /**
     * Define namespace
     * @param string
     */
    protected $namespace  =   'users';

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

        /**
         * In order to validate entries
         */
        $this->filterEntries        =   [
            'active'    =>  function( $value ) {
                return $value == 1 ? __( 'Active' ) : __( 'Unactive' );
            }
        ];

        /**
         * register entry actions hook
         * @todo register it on the generator
         */
        Hook::addFilter( 'crud.entry.actions', [ $this, 'setActions' ], 10, 3 );
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
         * If the current request process users namespace
         */
        $fields     =   $this->getFields( $user );

        if ( $request->route( 'namespace' ) == 'users' ) {

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
        if ( $namespace == 'users' ) {
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
            /**
             * @todo add to the generator
             */
            case 'main-route' : return $this->mainRoute ; break;
            case 'namespace' : return $this->namespace ; break;
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
    public function setActions( $actions, $row, $namespace )
    {
        if ( $namespace != 'users' ) {
            return $actions;
        }

        if ( Auth::id() == $row->id ) {
            unset( $actions[ 'delete' ] );
            $actions[ 'edit' ][ 'text' ]  =   __( 'My Profile' );
        } else {
            $actions[ 'delete' ][ 'text' ]  =   __( 'Delete User' );
            $actions[ 'edit' ][ 'text' ]  =   __( 'Edit User' );
        }
        return $actions;
    }

    /**
     * Bulk Delete Action
     * @param object Request with object
     * @return false/array
     */
    public function bulkDelete( Request $request ) 
    {
        $status     =   [
            'success'   =>  0,
            'danger'    =>  0
        ];
        
        if ( $request->input( 'action' ) == 'delete' ) {

            foreach ( $request->input( 'entries' ) as $id ) {
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
        }
        return $status;
    }

    /**
     * get Links
     * hold all link for differents screens
     * @return array of links
     */
    public function getLinks()
    {
        return  [
            'list'  =>  [
                [
                    'href'    =>  route( 'dashboard.users.create' ), 
                    'text' => __( 'Add a user' ),
                    'icon'  =>  'person_add'
                ]
            ],
            'create'    =>  [
                [
                    'href'    =>  route( 'dashboard.users.list' ), 
                    'text' => __( 'Return' ), 
                    'icon' => 'arrow_back'
                ]
            ],
            'edit'      =>  [
                [
                    'href'    =>  route( 'dashboard.users.list' ), 
                    'text' => __( 'Return' ), 
                    'class' => 'arrow_back' 
                ]
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

    /**
     * Bulk actions
     * register bulk action
     * it's used to check supported actions
     * @todo add it to the generator
     * @return array of actions
     */
    public function getBulkActions()
    {
        return [
            'delete'    =>  [
                'type'              =>  'GET',
                'text'              =>  __( 'Delete all' ),
                'icon'              =>  'delete_forever',
                'color'             =>  'warning',
                'shouldConfirm'     =>  true
            ]
        ];
    }
}