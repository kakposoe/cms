@inject( 'Hook', 'Tendoo\Core\Facades\Hook' )
@inject( 'Auth', 'Illuminate\Support\Facades\Auth' )
<nav id="toolbar" class="bg-white">
    <div class="row no-gutters align-items-center flex-nowrap">
        <div class="col">
            <div class="row no-gutters align-items-center flex-nowrap">
                <button type="button" class="toggle-aside-button btn btn-icon d-block d-lg-none" data-fuse-bar-toggle="aside">
                    <i class="icon icon-menu"></i>
                </button>
                <div class="toolbar-separator d-block d-lg-none"></div>
                <div class="shortcuts-wrapper row no-gutters align-items-center px-0 px-sm-2">
                    <div class="shortcuts row no-gutters align-items-center d-none d-md-flex">
                        @if( isset( $toolbarMenus ) )
                            @foreach( $toolbarMenus as $menu )
                            <a href="{{ $menu->href }}" class="shortcut-button btn btn-icon mx-1">
                                <i class="icon {{ $menu->icon }}"></i>
                            </a>
                            @endforeach
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="col-auto">

            <div class="row no-gutters align-items-center justify-content-end">

                <div class="user-menu-button dropdown">

                    @php
                        $email      = Auth::user()->email;
                        // $default    = asset( 'images/profile.png' );
                        // d=" . urlencode( $default ) . "&
                        $size       = 40;
                        $grav_url   = "https://www.gravatar.com/avatar/" . md5( strtolower( trim( $email ) ) ) . "?s=" . $size;
                    @endphp

                    <div class="dropdown-toggle ripple row align-items-center no-gutters px-2 px-sm-4" role="button" id="dropdownUserMenu"
                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <div class="avatar-wrapper">
                            <img class="avatar" src="{{ $grav_url }}">
                            <i class="status text-green icon-checkbox-marked-circle s-4"></i>
                        </div>
                        <span class="username mx-3 d-none d-md-block">{{ Auth::user()->username }}</span>
                    </div>

                    <div class="dropdown-menu" aria-labelledby="dropdownUserMenu">

                        @foreach( $Hook::filter( 'profile.menus', [
                            [
                                'label'     =>  __( 'Profile' ),
                                'href'      =>  route( 'dashboard.users.profile.general' ),
                                'icon'      =>  'icon-account'
                            ], [
                                'label'     =>  __( 'Logout' ),
                                'href'      =>  route( 'logout.index' ),
                                'divider_before'    =>      true,
                                'icon'      =>  'icon-logout'
                            ]
                        ]) as $menu )
                                
                            @php $menu   =  ( object ) $menu; @endphp

                            @if( @$menu->divider_before )
                                <div class="dropdown-divider"></div>
                            @endif

                            <a class="dropdown-item" href="{{ $menu->href }}">
                                <div class="row no-gutters align-items-center flex-nowrap">
                                    <i class="{{ $menu->icon }}"></i>
                                    <span class="px-3">{{ $menu->label }}</span>
                                </div>
                            </a>

                            @if( @$menu->divider_after )
                                <div class="dropdown-divider"></div>
                            @endif

                        @endforeach

                    </div>
                </div>
            </div>
        </div>
    </div>
</nav>