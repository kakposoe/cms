@inject( 'Auth', 'Illuminate\Support\Facades\Auth' )
@extends( 'tendoo::components.backend.master', [ 'parent_class' => 'app-body-container' ])
@section( 'tendoo::components.backend.master.body' )
    @include( 'tendoo::partials.backend.crud-table', [ 
        'namespace'     => 'users',
        'title'         =>  __( 'Users list' ),
        'description'   =>  __( 'Displays a list of all users' )
    ])
@endsection