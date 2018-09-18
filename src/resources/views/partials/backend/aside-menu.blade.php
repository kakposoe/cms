<ul 
    
    data-children=".nav-item" 

    <?php if ( @$parent === NULL ):?>
        class="nav flex-column custom-scrollbar aside-menu {{ @$tree > 0 ? 'sub-menu tree-' . $tree : '' }} pb-0"
        id="sidenav" 
    <?php else:?>
        class="collapse"
        id="{{ $parent }}-children-wrapper"
        role="tabpanel"
        data-children=".nav-item"
    <?php endif;?>
    >

    @foreach( $menus as $menu )
    <li {{ @$menu->childrens ? 'role="tab"' : null }} class="nav-item menu-{{ str_replace( '.', '-', $menu->namespace ) }}">
        <a 
            href="{{ @$menu->href ? $menu->href : '#' }}" 
            class="
                nav-link ripple 
                <?php echo @$menu->href == url()->current() ? 'active' : null;?> 
                <?php echo @$menu->childrens ? 'with-arrow collapsed' : null;?>
            "
            @if( @$menu->childrens )
                data-toggle="collapse" 
                data-target="#{{ $menu->namespace }}-children-wrapper"
            @endif
            >
            <span class="d-flex flex-row align-items-center">

                @if( @$menu->icon )
                <i 
                    style="font-size:18px;text-align:center;display:inline-block;line-height: 25px;" 
                    class="material-icons mr-2">
                    {{ $menu->icon }}
                </i>
                @endif
                
                <span>{{ $menu->text }}</span>
            </span>
        </a>
        @if( @$menu->childrens ) 
            @include( 'tendoo::partials.backend.aside-menu', [ 
                'menus'     =>  $menu->childrens,
                'parent'    =>  $menu->namespace, 
                'tree'      =>  intval( @$tree ) + 1 
            ]) 
        @endif
    </li>
    @endforeach
</ul>