@inject( 'Menus', 'Tendoo\Core\Services\Menus' ) 
@extends( 'tendoo::layouts.backend.master' ) 
@section( 'tendoo::layouts.backend.master.body' )
    <!-- Always shows a header, even in smaller screens. -->
    <div class="mdl-layout mdl-js-layout mdl-layout--fixed-header">
    <header class="mdl-layout__header">
        <div class="mdl-layout__header-row">
        <!-- Title -->
        <span class="mdl-layout-title">Title</span>
        <!-- Add spacer, to align navigation to the right -->
        <div class="mdl-layout-spacer"></div>
        <!-- Navigation. We hide it in small screens. -->
        <nav class="mdl-navigation mdl-layout--large-screen-only">
            <a class="mdl-navigation__link" href="">Link</a>
            <a class="mdl-navigation__link" href="">Link</a>
            <a class="mdl-navigation__link" href="">Link</a>
            <a class="mdl-navigation__link" href="">Link</a>
        </nav>
        </div>
    </header>
    <div class="mdl-layout__drawer">
        <span class="mdl-layout-title">Title</span>
        @include( 'tendoo::partials.backend.md-aside', [
            'menus'     =>  $Menus->get(),
            'tree'      =>  0
        ])
    </div>
    <main class="mdl-layout__content">
        <div class="page-content">Hello World</div>
    </main>
    </div>
@endsection