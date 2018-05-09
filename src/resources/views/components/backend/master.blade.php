@inject( 'Menus', 'Tendoo\Core\Services\Menus' )
@inject( 'Hook', 'Tendoo\Core\Facades\Hook' )
@inject( 'UserOptions', 'Tendoo\Core\Services\UserOptions' )
@extends( 'tendoo::layouts.backend.master' )
@section( 'tendoo::layouts.backend.master.body' )
    @include( 'tendoo::partials.backend.aside', [
        'menus'     =>  $Hook::filter( 'dashboard.menus', $Menus->get() ),
        'tree'      =>  0
    ])
    @include( 'tendoo::partials.backend.toolbar' )
    <v-content>
        @yield( 'tendoo::components.backend.master.body' )
    </v-content>
@endsection

