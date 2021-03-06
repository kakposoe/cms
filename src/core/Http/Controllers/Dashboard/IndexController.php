<?php

namespace Tendoo\Core\Http\Controllers\Dashboard;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Tendoo\Core\Http\Controllers\DashboardController;
use Tendoo\Core\Services\Options;
use Tendoo\Core\Services\UserOptions;

class IndexController extends DashboardController
{
    public function __construct()
    {
        parent::__construct();
        $this->middleware( function( $request, $next ) {
            return $next( $request );
        });
    }

    
    /** 
     * Dashboard index
     * @since 1.0
     */
    public function index( UserOptions $userOptions )
    {
        $this->setTitle( __( 'Dashboard Index' ) );
        return view( 'tendoo::components.backend.index' );
    }
}
