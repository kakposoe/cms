@inject( 'UserOptions', 'Tendoo\Core\Services\UserOptions' )
@inject( 'Auth', 'Illuminate\Support\Facades\Auth' )
@section( 'partials.shared.head' )
    <link rel="stylesheet" href="{{ asset( 'tendoo/css/main.css' ) }}">
    <!-- Icons.css -->
    <link type="text/css" rel="stylesheet" href="{{ asset( 'tendoo/icons/fuse-icon-font/style.css' ) }}" />
    <!-- Animate.css -->
    <link type="text/css" rel="stylesheet" href="{{ asset( 'tendoo/node_modules/animate.css/animate.min.css' ) }}" />
    <!-- PNotify -->
    <link type="text/css" rel="stylesheet" href="{{ asset( 'tendoo/node_modules/pnotify/dist/PNotifyBrightTheme.css' ) }}" />
    <!-- Nvd3 - D3 Charts -->
    <link type="text/css" rel="stylesheet" href="{{ asset( 'tendoo/node_modules/nvd3/build/nv.d3.min.css' ) }}" />
    <!-- Perfect Scrollbar -->
    <link type="text/css" rel="stylesheet" href="{{ asset( 'tendoo/node_modules/perfect-scrollbar/css/perfect-scrollbar.css' ) }}" />
    <!-- Fuse Html -->
    <link type="text/css" rel="stylesheet" href="{{ asset( 'tendoo/fuse-html/fuse-html.min.css' ) }}" />
    <!-- Main CSS -->
    <link type="text/css" rel="stylesheet" href="{{ asset( 'tendoo/css/main.css' ) }}" />
    
    <script type="text/javascript" src="{{ asset( 'tendoo/node_modules/jquery/dist/jquery.min.js' ) }}"></script>
    <!-- Mobile Detect -->
    <script type="text/javascript" src="{{ asset( 'tendoo/node_modules/mobile-detect/mobile-detect.min.js' ) }}"></script>
    <!-- Perfect Scrollbar -->
    <script type="text/javascript" src="{{ asset( 'tendoo/node_modules/perfect-scrollbar/dist/perfect-scrollbar.min.js' ) }}"></script>
    <!-- Popper.js -->
    <script type="text/javascript" src="{{ asset( 'tendoo/node_modules/popper.js/dist/umd/popper.min.js' ) }}"></script>
    <!-- Bootstrap -->
    <script type="text/javascript" src="{{ asset( 'tendoo/node_modules/bootstrap/dist/js/bootstrap.min.js' ) }}"></script>
    <!-- Nvd3 - D3 Charts -->
    <script type="text/javascript" src="{{ asset( 'tendoo/node_modules/d3/d3.min.js' ) }}"></script>
    <script type="text/javascript" src="{{ asset( 'tendoo/node_modules/nvd3/build/nv.d3.min.js' ) }}"></script>
    <!-- Data tables -->
    <script type="text/javascript" src="{{ asset( 'tendoo/node_modules/datatables.net/js/jquery.dataTables.js' ) }}"></script>
    <script type="text/javascript" src="{{ asset( 'tendoo/node_modules/datatables-responsive/js/dataTables.responsive.js' ) }}"></script>
    <!-- PNotify -->
    <script type="text/javascript" src="{{ asset( 'tendoo/node_modules/pnotify/dist/iife/PNotify.js' ) }}"></script>
    <script type="text/javascript" src="{{ asset( 'tendoo/node_modules/pnotify/dist/iife/PNotifyStyleMaterial.js' ) }}"></script>
    <script type="text/javascript" src="{{ asset( 'tendoo/node_modules/pnotify/dist/iife/PNotifyButtons.js' ) }}"></script>
    <script type="text/javascript" src="{{ asset( 'tendoo/node_modules/pnotify/dist/iife/PNotifyCallbacks.js' ) }}"></script>
    <script type="text/javascript" src="{{ asset( 'tendoo/node_modules/pnotify/dist/iife/PNotifyMobile.js' ) }}"></script>
    <script type="text/javascript" src="{{ asset( 'tendoo/node_modules/pnotify/dist/iife/PNotifyHistory.js' ) }}"></script>
    <script type="text/javascript" src="{{ asset( 'tendoo/node_modules/pnotify/dist/iife/PNotifyDesktop.js' ) }}"></script>
    <script type="text/javascript" src="{{ asset( 'tendoo/node_modules/pnotify/dist/iife/PNotifyConfirm.js' ) }}"></script>
    <script type="text/javascript" src="{{ asset( 'tendoo/node_modules/pnotify/dist/iife/PNotifyReference.js' ) }}"></script>
    <!-- Fuse Html -->
    <script type="text/javascript" src="{{ asset( 'tendoo/fuse-html/fuse-html.min.js' ) }}"></script>
    <!-- Main JS -->
    <script type="text/javascript" src="{{ asset( 'tendoo/js/main.js' ) }}"></script>
@endsection
@section( 'partials.shared.footer' )
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
