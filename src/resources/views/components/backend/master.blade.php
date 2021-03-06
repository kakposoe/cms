@inject( 'Menus', 'Tendoo\Core\Services\Menus' )
@inject( 'Hook', 'Tendoo\Core\Facades\Hook' )
@inject( 'UserOptions', 'Tendoo\Core\Services\UserOptions' )
@extends( 'tendoo::layouts.backend.master' )
@section( 'tendoo::layouts.backend.master.body' )
    <div class="app-wrapper {{ $UserOptions->get( 'dashboard-aside' ) === 'collapsed-aside' ? 'collapsed-aside-no-anim' : 'expanded-aside-no-anim' }} h-100 d-flex align-items-stretch flex-row bmd-layout-container bmd-drawer-f-l">
        <aside id="sidebar-menu" class="h-100 d-flex flex-column">
            <div class="sidebar-logo align-items-center d-flex justify-content-center">
            <b>Tend</b>oo
            </div>
            <div class="menu-wrapper h-100" data-simplebar>
            @include( 'tendoo::partials.backend.aside', [
                'menus'     =>  $Hook::filter( 'dashboard.menus', $Menus->get() ),
                'tree'      =>  0
            ])
            </div>
        </aside>
        <div id="app-body" class="align-items-stretch h-100 flex-column d-flex">
            @include( 'tendoo::partials.backend.navbar' )
            <div class="h-100 app-body-container {{ @$parent_class ? $parent_class : 'p-3' }}">
                @yield( 'tendoo::components.backend.master.body' )
            </div>
        </div>
        <div class="bmd-layout-backdrop in"></div>
    </div>
@endsection