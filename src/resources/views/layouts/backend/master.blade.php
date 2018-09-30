@inject( 'UserOptions', 'Tendoo\Core\Services\UserOptions' )
@inject( 'Auth', 'Illuminate\Support\Facades\Auth' )
@section( 'partials.shared.head' )
@endsection
@section( 'partials.shared.footer' )
    <script>
        var tendooApi               =   new Object;

        /**
         * hold routes url to the options
         * of the system
         */
        // tendooApi.options           =   new Options({
        //     postUrl         :   "{{ route( 'ajax.set.options' ) }}",
        //     getUrl          :   "{{ route( 'ajax.get.options' ) }}",
        //     deleteUrl       :   "{{ route( 'ajax.delete.options' ) }}"
        // });

        /**
         * Hold the current route to have access
         * to the user options
         */
        // tendooApi.userOptions       =   new Options({
        //     id              :   {!! $Auth::id() !!},
        //     postUrl         :   "{{ route( 'user-ajax.set.options' ) }}",
        //     getUrl          :   "{{ route( 'user-ajax.get.options' ) }}",
        //     deleteUrl       :   "{{ route( 'user-ajax.delete.options' ) }}"
        // });
    </script>
    <script>
    // $(document).ready(function() { 
    //     // $('body').bootstrapMaterialDesign(); 
    //     // mdc.autoInit();
    //     window.mdc.autoInit();
    //     console.log( mdc );
    // });
    </script>
    <script>
    /**
     * Tendoo SnackBar
     */
    // tendooApi.SnackBar  =   new MdSnackbar();
    </script>
@endsection
@include( 'tendoo::partials.shared.header' )
    @yield( 'tendoo::layouts.backend.master.body' ) 
@include( 'tendoo::partials.shared.footer' )
