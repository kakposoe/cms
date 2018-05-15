@inject( 'UserOptions', 'Tendoo\Core\Services\UserOptions' )
@inject( 'Auth', 'Illuminate\Support\Facades\Auth' )
@section( 'partials.shared.head' )
    <link href='https://fonts.googleapis.com/css?family=Roboto:300,400,500,700|Material+Icons' rel="stylesheet">
    <link href="{{ asset( 'tendoo/css/backend.css' ) }}" rel="stylesheet">
    <link href="{{ asset( 'tendoo/css/vuetify.css' ) }}" rel="stylesheet">
    <script src="{{ asset( 'tendoo/bower_components/jquery/dist/jquery.min.js' ) }}"></script>
    <script src="{{ asset( 'tendoo/bower_components/vue/dist/vue.min.js' ) }}"></script>
    <script src="{{ asset( 'tendoo/bower_components/axios/dist/axios.min.js' ) }}"></script>
    <script src="{{ asset( 'tendoo/js/vuetify.js' ) }}"></script>
    <script>
    var TendooEvent     =   new Vue();
    var OptionsData    =   {
        id              :   {!! $Auth::id() !!},
        postUrl         :   "{{ route( 'ajax.set.options' ) }}",
        getUrl          :   "{{ route( 'ajax.get.options' ) }}",
        deleteUrl       :   "{{ route( 'ajax.delete.options' ) }}",
        drawer          :   false
    }
    </script>
    <script src="{{ asset( 'tendoo/js/dashboard/options.js' ) }}"></script>
    <script>
    var tendooApi           =   new Object;
    </script>
@endsection
@section( 'partials.shared.footer' )
    @stack( 'vue.components' )
    <script>
    new Vue({ 
        el: '#vue-application',
        data : {
            dialog: false,
            drawer: null,
        },
        props: {
            source: ''
        },
        mounted() {
            $( '#vue-application' ).show();
        }
    });
    </script>
@endsection
@include( 'tendoo::partials.shared.header' )
<div id="vue-application" style="display:none">
    <v-app id="inspire">
        @yield( 'tendoo::layouts.backend.master.body' )
        @stack( 'vue.footer' )
    </v-app>
</div>
@include( 'tendoo::partials.shared.footer' )