<?php
use Illuminate\Http\Request;

Route::middleware([ 'api.guard' ])
    ->prefix( 'api' )
    ->group( function() {
        Route::get( 'status', 'ApiAppController@status' );
        Route::post( 'setup/database', 'ApiAppController@setupDatabase' );
        Route::post( 'setup/details', 'ApiAppController@setupDatabase' );

        Route::get( 'modules', 'ApiModulesController@modules' );
        Route::get( 'module/{namespace}', 'ApiModulesController@module' );
        Route::post( 'module', 'ApiModulesController@upload' );
        Route::post( 'module/{namespace}/{action}', 'ApiModulesController@changeState' );
        Route::module( 'module/{namespace}', 'ApiModulesController@remove' );
        Route::delete( 'module/{namespace}', 'ApiModulesController@uninstall' );

        Route::get( 'users', 'ApiUsersController@users' );

        Route::get( 'user/{id}', 'ApiUsersController@user' );
        Route::post( 'user', 'ApiUsersController@create' );
        Route::put( 'user', 'ApiusersController@update' );
        Route::delete( 'user/{id}', 'ApiUsersController@delete' );
});

