@inject( 'Field', 'Tendoo\Core\Services\Field' )
@extends( 'tendoo::components.backend.master', [ 'parent_class' => 'app-body-container' ])
@section( 'tendoo::components.backend.master.body' )
    @include( 'tendoo::partials.backend.crud-edit-form', [ 
        'namespace'     => 'users',
        'title'         =>  __( 'Edit a user' ),
        'description'   =>  __( 'Update an available user on the system.' )
    ])
@endsection