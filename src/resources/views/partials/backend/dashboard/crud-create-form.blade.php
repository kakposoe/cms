@inject( 'Hook', 'Tendoo\Core\Facades\Hook' )
@php
    $resource   =   $Hook::filter( 'define.crud', null, $namespace );
@endphp

@if ( ! is_object( $resource ) )
    @include( 'tendoo::errors.unhandled-crud' )
@else 
<div class="content-wrapper">
    @include( 'tendoo::partials.shared.page-title', [
        'title'         =>  @$resource->create_title ? $resource->create_title : __( 'Undefined Page' ),
        'description'   =>  @$resource->create_description ? $resource->create_description : __( 'Undefined Description' ),
        'links'         =>  $resource->getLinks()[ 'create' ]
    ])
    <div class="content-body">
        <div class="container-fluid pt-4 pb-4 px-xs-0 px-lg-4">
            <form class="mb-0" action="{{ route( 'dashboard.crud.post', [ 'namespace' => $resource->getNamespace() ] ) }}" enctype="multipart/form-data" method="post">
                <div class="card">
                    <div class="card-header p-0">
                        <h5 class="box-title">{{ @$resource->create_title ? $resource->create_title : __( 'Undefined Page' ) }}</h5>
                    </div>
                    <div class="card-body p-0">
                    @include( 'tendoo::partials.shared.errors', compact( 'errors' ) )
                    </div>
                    {{ csrf_field() }}
                    <div class="card-body p-3">
                        @each( 'tendoo::partials.shared.fields', $resource->getFields(), 'field' )
                    </div>
                    <div class="p-2 card-footer">
                        <button type="submit" class="mb-0 btn btn-raised btn-primary">{{ @$resource->create_button ? $resource->create_button : __( 'Create' ) }}</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endif