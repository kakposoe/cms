@inject( 'Modules', 'Tendoo\Core\Services\Modules' )
@section( 'partials.shared.head' )
    @parent
    <link rel="stylesheet" href="{{ asset( 'css/backend-module.css' ) }}">
@endsection

@extends( 'tendoo::components.backend.master', [ 'parent_class' => 'app-body-container' ] )
@section( 'tendoo::components.backend.master.body' )
    <div class="h-100 content-wrapper">
        @include( 'tendoo::partials.shared.page-title', [
            'title'     =>  __( 'Upload a module' ),
            'description'   =>  __( 'Add feature by uploading a Tendoo CMS module' ),
            'links'   =>  [
                [ 
                    'href'  =>  route( 'dashboard.modules.list' ),
                    'text'  =>  __( 'Return' ),
                    'class' =>  'btn btn-secondary btn-raised'
                ]
            ]
        ])
        <div class="content-body">
            <div class="container-fluid pt-3 p-4">
                <form action="{{ route( 'dashboard.modules.post' ) }}" enctype="multipart/form-data" method="post">
                    <div class="card">
                        <div class="card-header p-0">
                            <h5 class="box-title">{{ __( 'Please choose the file to upload' ) }}</h5>
                        </div>
                        <div class="card-body p-0">
                        @include( 'tendoo::partials.shared.errors', compact( 'errors' ) )
                        </div>
                        {{ csrf_field() }}
                        <div class="card-body p-3">
                            <div class="form-group mb-0">
                                <input type="file" name="module" id="module-file">
                                @if ( $errors->has( 'module' ) )
                                <div class="invalid-feedback d-block">
                                    {{ $errors->first( 'module' ) }}
                                </div>
                                @endif
                            </div>
                        </div>
                        <div class="p-2 card-footer">
                            <button type="submit" class="mb-0 btn btn-raised btn-primary">{{ __( 'Upload' ) }}</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection