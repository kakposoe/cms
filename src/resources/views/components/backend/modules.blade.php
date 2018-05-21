@inject( 'Modules', 'Tendoo\Core\Services\Modules' ) 
@section( 'partials.shared.head' ) 
@extends( 'tendoo::components.backend.master', [ 'parent_class' => 'app-body-container' ] ) 
@section( 'tendoo::components.backend.master.body' )
@php
$collection     =   collect( $Modules->get() );
$rowsModules    =   $collection->chunk(3);
$tabs           =   [
    [
        'namespace' =>  'list',
        'title'     =>  __( 'List' )
    ], [
        'namespace' =>  'install',
        'title'     =>  __( 'Install' )
    ], [
        'namespace' =>  'store',
        'title'     =>  __( 'Store' )
    ]
]
@endphp
<app-modules inline-template>
    <v-container fluid class="no-padding" fill-height>
        <v-layout column>
            <v-flex d-block style="height: 160px;flex: none" class="primary pa-4">
                <v-layout column>
                    <v-flex>
                        <v-layout row>
                            <v-flex class="white--text">
                                <span class="display-1">{{ __( 'Modules' )}}</span><br>
                                <span>{{ __( 'Manage modules installed on the system.' ) }}</span>
                            </v-flex>
                            <v-flex lg8 d-flex align-end>
                                <v-spacer></v-spacer>
                                <div style="flex: none !important;">

                                </div>
                            </v-flex>
                        </v-layout>
                    </v-flex>
                </v-layout>
            </v-flex>
            <v-flex class="ma-4 modules-box">
                <div class="elevation-1">
                    <v-tabs
                        v-model="active"
                        color="cyan"
                        dark
                        slider-color="yellow"
                        >
                        <v-tab
                            v-for="tab in tabs"
                            :key="tab.namespace"
                            ripple
                            >
                            @{{ tab.title }}
                        </v-tab>
                        <v-tab-item
                            v-for="tab in tabs"
                            :key="tab.namespace"
                        >
                            <v-layout row>
                                <v-flex xs12 sm6 md4 lg3 xl3 v-for="module of modules">
                                    <v-card>
                                        <v-card-title primary-title>
                                            <div>
                                                <h3 class="headline mb-0">@{{ module.name }}</h3>
                                                <div>@{{ module.description }} <small>( @{{ module.version }} )</small></div>
                                            </div>
                                        </v-card-title>
                                        <v-card-actions>
                                            <v-btn-toggle v-model="module.toggles">
                                                <v-btn v-if="module.enabled" :href="'{{ route( 'dashboard.modules.enable', [ 'namespace' => '' ] ) }}/' + module.namespace" flat color="orange">
                                                    <v-icon>check_circle</v-icon>
                                                </v-btn>
                                                <v-btn v-else-if="! module.enabled" :href="'{{ route( 'dashboard.modules.disable', [ 'namespace' => '' ] ) }}'/ + module.namespace" flat color="primary">
                                                    <v-icon>highlight_off</v-icon>
                                                </v-btn>
                                                <v-btn :href="'{{ route( 'dashboard.modules.delete', [ 'namespace' => '' ] ) }}/' + module.namespace" flat color="red">
                                                    <v-icon>delete_forever</v-icon>
                                                </v-btn>
                                                <v-btn :href="'{{ route( 'dashboard.modules.extract', [ 'namespace' => '' ] ) }}/' + module.namespace" flat color="green">
                                                    <v-icon>file_download</v-icon>
                                                </v-btn>
                                            </v-btn-toggle>
                                        </v-card-actions>
                                    </v-card>
                                </v-flex>
                            </v-layout>
                        </v-tab-item>
                    </v-tabs>
                </div>
            </v-flex>
        </v-layout>
    </v-container>
</app-modules>
@endsection
@push( 'vue.components' )
<script>
var data    =   {
    modules     :   @json( $collection ),
    tabs        :   @json( $tabs )
}
Vue.component( 'app-modules', {
    data() {
        return Object.assign({}, data, {
            active: null,
            text: 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.'
        });
    },
})
</script>
<!-- <script src="{{ asset( 'tendoo/js/components/modules.js' ) }}"></script> -->
<style>
/* @todo export this to the backend.css */
.modules-box {
    position: relative;
    top: -72px;
}
</style>
@endpush