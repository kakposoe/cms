@inject( 'Menus', 'Tendoo\Core\Services\Menus' )
@inject( 'Hook', 'Tendoo\Core\Facades\Hook' )
@inject( 'UserOptions', 'Tendoo\Core\Services\UserOptions' )
<aside id="aside" class="aside aside-left" data-fuse-bar="aside" data-fuse-bar-media-step="md" data-fuse-bar-position="left">
    <div class="aside-content bg-primary-700 text-auto">

        <div class="aside-toolbar">

            <div class="logo">
                <span class="logo-icon">F</span>
                <span class="logo-text">FUSE</span>
            </div>

            <button id="toggle-fold-aside-button" type="button" class="btn btn-icon d-none d-lg-block" data-fuse-aside-toggle-fold>
                <i class="icon icon-backburger"></i>
            </button>

        </div>

        @include( 'tendoo::partials.backend.aside-menu', [
            'menus'     =>  $Menus->get(),
            'tree'      =>  0,
            'parent'    =>  null,
        ]);
        
    </div>

</aside>