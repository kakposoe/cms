@inject( 'UserOptions', 'Tendoo\Core\Services\UserOptions' )
@inject( 'Auth', 'Illuminate\Support\Facades\Auth' )
@section( 'partials.shared.head' )
    <link rel="stylesheet" href="{{ asset( 'tendoo/node_modules/material-components-web/dist/material-components-web.css' ) }}">
    <link rel="stylesheet" href="{{ asset( 'tendoo/css/backend.css' ) }}">
    <link rel="stylesheet" href="{{ asset( 'tendoo/css/themes/' . $UserOptions->get( 'theme_class' ) . '.css' ) }}">
    <!-- Scroll Bar CSS -->
    <link rel="stylesheet" href="{{ asset( 'tendoo/css/simplebar.css' ) }}" />

    <script src="{{ asset( 'tendoo/bower_components/jquery/dist/jquery.min.js' ) }}"></script>
    <script src="{{ asset( 'tendoo/js/jquery.extended.js' ) }}"></script>
    <script src="{{ asset( 'tendoo/js/truncate.vue.js' ) }}"></script>
    <script src="{{ asset( 'tendoo/bower_components/vue/dist/vue.min.js' ) }}"></script>
    <script src="{{ asset( 'tendoo/bower_components/axios/dist/axios.min.js' ) }}"></script>
    <script src="{{ asset( 'tendoo/bower_components/vue-router/dist/vue-router.min.js' ) }}"></script>
@endsection
@section( 'partials.shared.footer' )
    <script src="{{ asset( 'tendoo/js/dashboard/options.js' ) }}"></script>
    <script>
        var tendooApi               =   new Object;

        /**
         * hold routes url to the options
         * of the system
         */
        tendooApi.options           =   new Options({
            postUrl         :   "{{ route( 'ajax.set.options' ) }}",
            getUrl          :   "{{ route( 'ajax.get.options' ) }}",
            deleteUrl       :   "{{ route( 'ajax.delete.options' ) }}"
        });

        /**
         * Hold the current route to have access
         * to the user options
         */
        tendooApi.userOptions       =   new Options({
            id              :   {!! $Auth::id() !!},
            postUrl         :   "{{ route( 'user-ajax.set.options' ) }}",
            getUrl          :   "{{ route( 'user-ajax.get.options' ) }}",
            deleteUrl       :   "{{ route( 'user-ajax.delete.options' ) }}"
        });
    </script>
    
    <script src="{{ asset( 'tendoo/bower_components/popper.js/dist/umd/popper.min.js' ) }}"></script>
    <script src="{{ asset( 'tendoo/bower_components/bootstrap-material-design/js/bootstrap-material-design.js' ) }}"></script>
    <script src="{{ asset( 'tendoo/node_modules/material-components-web/dist/material-components-web.js' ) }}"></script>
    <script src="{{ asset( 'tendoo/js/dashboard/md-snackbar.js' ) }}"></script>
    <script src="{{ asset( 'tendoo/js/dashboard/aside.vue.js' ) }}"></script>
    <script src="{{ asset( 'tendoo/js/dashboard/navbar.vue.js' ) }}"></script>
    <script>
    $(document).ready(function() { 
        // $('body').bootstrapMaterialDesign(); 
        // mdc.autoInit();
        window.mdc.autoInit();
        console.log( mdc );
    });
    </script>
    <!-- Scroll Bar JS -->
    <script src="{{ asset( 'tendoo/js/simplebar.js' ) }}"></script>
    <script>
    /**
     * Tendoo SnackBar
     */
    tendooApi.SnackBar  =   new MdSnackbar();
    </script>
@endsection
@include( 'tendoo::partials.shared.header' )
    @yield( 'tendoo::layouts.backend.master.body' ) 
@include( 'tendoo::partials.shared.footer' )
