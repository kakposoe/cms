@inject( 'Modules', 'Tendoo\Core\Services\Modules' )
@extends( 'tendoo::components.backend.master', [ 'parent_class' => 'app-body-container' ] )
@push( 'partials.shared.footer' )
<style type="text/css">
    div.page-layout.simple>div.page-header.small-page-header {
        height: 7.6rem;
        min-height: 7.6rem;
        max-height: 7.6rem;
    }
    .row-disabled {
        background: #EEE !important;
    }
</style>
@endpush
<?php
$collection     =   collect( $Modules->get() );
$rowsModules    =   $collection->chunk(3);
$class          =   'm-0';
?>
@section( 'tendoo::components.backend.master.body' )
<div class="page-layout simple left-sidebar-floating">
    <div class="page-header small-page-header bg-secondary text-auto row no-gutters justify-content-between p-4 p-sm-6">
        <div class="col">
            <div class="row no-gutters align-items-center flex-nowrap">
                <button type="button" class="sidebar-toggle-button btn btn-icon d-inline-block d-lg-none mr-2 fuse-ripple-ready"
                    data-fuse-bar-toggle="contacts-sidebar">
                    <i class="icon icon-menu"></i>
                </button>
                <!-- APP TITLE -->
                <div class="logo row no-gutters align-items-center flex-nowrap">
                    <span class="logo-icon mr-4">
                        <i class="icon-account-box s-6"></i>
                    </span>
                    <span class="logo-text h4">{{ __( 'Modules' ) }}</span>
                </div>
            </div>
            <!-- / APP TITLE -->
        </div>
        <!-- SEARCH -->
        <div class="search-wrapper">
            <div class="input-group">
                <a href="{{ route( 'dashboard.modules.upload' ) }}" class="btn btn-icon fuse-ripple-ready">
                    <i class="icon icon-upload"></i>
                </a>
            </div>
        </div>
        <!-- / SEARCH -->
    </div>
    <div class="page-content-wrapper">
        <!-- CONTENT -->
        <div class="page-content p-4 p-sm-6">
            <!-- CONTACT LIST -->
            <div class="contacts-list card">

                <!-- CONTACT LIST HEADER -->
                <div class="contacts-list-header p-4">

                    <div class="row no-gutters align-items-center justify-content-between">

                        <div class="list-title text-muted">
                            {{ sprintf( __( 'All modules (%s)' ), $collection->count() ) }}
                        </div>

                        <button type="button" class="btn btn-icon fuse-ripple-ready">
                            <i class="icon icon-sort-alphabetical"></i>
                        </button>
                    </div>

                </div>
                <!-- / CONTACT LIST HEADER -->
                @if( $collection->isNotEmpty() )
                    @foreach( $rowsModules as $rowModule)
                        @foreach( $rowModule as $module )
                <!-- CONTACT ITEM -->
                <div class="@if( ! $module[ 'enabled' ] ) row-disabled @endif row no-gutters align-items-center py-2 px-3 py-sm-4 px-sm-6">

                    <label class="custom-control custom-checkbox">
                        <input type="checkbox" class="custom-control-input">
                        <span class="custom-control-indicator fuse-ripple-ready"></span>
                    </label>

                    <div class="col px-3">
                        <div class="text-truncate font-weight-bold">{{ $module[ 'name' ] }}</div>

                        <div class="d-flex flex-row">
                            <div class="email text-truncate">
                                {{ $module[ 'version' ] }}
                            </div>
                            <div class="col phone text-truncate px-1 d-none d-xl-flex">
                                {{ $module[ 'author' ] }}
                            </div>
                        </div>
                    </div>

                    <div class="col-auto actions">

                        <div class="row no-gutters row">

                            <div>
                                @if( ! $module[ 'enabled' ] )
                                    <a href="{{ route( 'dashboard.modules.enable', [ 'namespace' => $module[ 'namespace' ] ] ) }}" class="mb-0 btn btn-icon btn-raised">
                                        <i class="icon-check-circle"></i>
                                    </a>
                                @else 
                                    <a href="{{ route( 'dashboard.modules.disable', [ 'namespace' => $module[ 'namespace' ] ] ) }}" class="mb-0 btn btn-icon btn-raised">
                                        <i class="icon-cancel"></i>
                                    </a>
                                @endif

                                @if( $Modules->getMigrations( $module[ 'namespace' ] ) ) 
                                    <a href="{{ route( 'dashboard.modules.migration', [ 'namespace' => $module[ 'namespace' ] ] ) }}" class="mb-0 btn btn-icon btn-raised">{{ __( 'Migrate' ) }}</a>
                                @endif
                            </div>

                            <div>
                                <a onclick="return confirm( '{{ __( 'Would you like to delete this module ?' ) }}' )" href="{{ route( 'dashboard.modules.delete', [ 'namespace' => $module[ 'namespace' ] ] ) }}" class="btn-icon btn btn-raised">
                                    <i class="material-icons">delete_forever</i>
                                </a>
                                <a class="btn btn-icon fuse-ripple-ready" href="{{ route( 'dashboard.modules.extract', [ 'namespace' => $module[ 'namespace' ] ] ) }}" >
                                    <i class="icon-box-download"></i>
                                </a>
                            </div>

                        </div>
                    </div>
                </div>
                <!-- CONTACT ITEM -->
                        @endforeach
                    @endforeach
                @endif

            </div>
            <!-- / CONTACT LIST -->
        </div>
        <!-- / CONTENT -->
    </div>
</div>
@endsection