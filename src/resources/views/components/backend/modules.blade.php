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
        'namespace' =>  'upload',
        'title'     =>  __( 'Upload' )
    ]
];
$textDomain     =   [
    'removeConfirm'     =>  __( 'Would you like to remove this module ?' ),
    'uploading'         =>  __( 'Uploading...' ),
    'wrongFileType'     =>  __( 'A wrong file type has been selected. Only ".zip" files are allowed' ),
    'uploadComplete'    =>  __( 'The module has been correctly uploaded.' ),
    'requireFile'       =>  __( 'Please select a file !' )
];
@endphp
<app-modules inline-template>
    <v-container fluid class="no-padding grey lighten-2" fill-height>
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
                            <template v-if="tab.namespace == 'list'" v-for="modules of modulesRows">
                                <v-layout class="white">
                                    <template row>
                                        <v-flex d-flex xs12 sm6 md4 lg3 xl3 v-for="module of modules">
                                            <v-layout column>
                                                <v-container fluid grid-list-lg>
                                                    <div>
                                                        <div class="headline">@{{ module.name }}</div>
                                                        <div>@{{ module.description }}</div>
                                                    </div>
                                                </v-container>
                                                <div><v-divider></v-divider></div>
                                                <div class="module-actions">
                                                    <v-btn class="module-buttons" v-if="! module.enabled" :href="'{{ route( 'dashboard.modules.enable', [ 'namespace' => '' ] ) }}/' + module.namespace" flat color="primary">
                                                        {{ __( 'Enable' ) }}
                                                    </v-btn>
                                                    <v-btn class="module-buttons" v-else-if="module.enabled" :href="'{{ route( 'dashboard.modules.disable', [ 'namespace' => '' ] ) }}/' + module.namespace" flat color="primary">
                                                        {{ __( 'Disable' ) }}
                                                    </v-btn>
                                                    <v-btn class="module-buttons" @click="confirmModuleRemove( '{{ route( 'dashboard.modules.delete', [ 'namespace' => '' ] ) }}/' + module.namespace )" flat color="red">
                                                        {{ __( 'Remove' ) }}
                                                    </v-btn>
                                                    <v-btn class="module-buttons" :href="'{{ route( 'dashboard.modules.extract', [ 'namespace' => '' ] ) }}/' + module.namespace" flat color="green">
                                                        <v-icon>file_download</v-icon>
                                                    </v-btn>
                                                </div>
                                            </v-layout>
                                        </v-flex>
                                    </template>
                                </v-layout>
                                <v-divider></v-divider>
                            </template>

                            <template v-if="tab.namespace == 'upload'">
                                <div class="white">
                                    <div class="px-2 pt-2">
                                        <v-alert :value="true" type="info">
                                            {{ __( 'Please select the zip file that you would like to upload as a module' ) }}
                                        </v-alert>
                                    </div>
                                    <form @submit="asyncSubmit( $event )" enctype="multipart/form-data" method="post">
                                        <div class="pa-2">
                                            <input type="file" v-model="module" name="module" id="module-field">
                                        </div>
                                        <v-divider></v-divider>
                                        <div>
                                            <v-btn :disabled="isUploading" color="success" type="submit">{{ __( 'Upload' ) }}</v-btn>
                                        </div>
                                    </form>
                                </div>
                            </template>
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
    tabs        :   @json( $tabs ),
    perLines    :   4,
    csrfToken   :   '{{ csrf_token() }}',
    textDomain  :   @json( $textDomain ),
    url     :   {
        upload  :   "{{ route( 'dashboard.modules.post' ) }}"
    }
}
Vue.component( 'app-modules', {
    data() {
        return Object.assign({}, data, {
            module: "",
            active: null,
            isUploading: false,
            text: 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.'
        });
    },
    methods : {
        confirmModuleRemove( link ) {
            if ( confirm( this.textDomain.removeConfirm ) ) {
                document.location   =   link;
            }
        },

        asyncSubmit( event ) {
            event.preventDefault();
            let files           =   $( '#module-field' )[0].files;
            let form            =   new FormData();
            let xhr             =   new XMLHttpRequest();

            if ( files[0] === undefined ) {
                return TendooEvent.$emit( 'show.snackbar', { message : this.textDomain.requireFile });
            }

            if ( files[0].type === 'application/x-zip-compressed' ) {
                // Ten dooEvent.$emit( 'show.snackbar', { message : this.textDomain.uploading, status : 'info', duration : 1000 });
                this.isUploading    =   true;
                form.append( 'module', files[0], files[0].name );
                form.append( 'csrf-token', this.csrfToken );

                xhr.open( 'POST', this.url.upload, true );
                xhr.setRequestHeader( 'X-CSRF-TOKEN', this.csrfToken );
                xhr.setRequestHeader( 'X-Requested-With', 'XMLHttpRequest' );
                xhr.onload      =   ( res ) => {
                    let response    =   JSON.parse( xhr.response );
                    console.log( response.message );
                    TendooEvent.$emit( 'show.snackbar', {
                        message     :   response.message
                    });
                    this.isUploading    =   false;
                }
                xhr.send( form );

            } else {
                this.module     =   '';
                TendooEvent.$emit( 'show.snackbar', { message : this.textDomain.wrongFileType })
            }
        }
    },
    computed : {
        modulesRows() {
            let totalLines  =   Math.round( Object.values( this.modules ).length / this.perLines );
            let modulesRows     =   [];
            for ( let i = 0; i < totalLines ; i++ ) {
                modulesRows[i]  =   Object.values( this.modules ).splice( i * this.perLines, this.perLines );
            }
            return modulesRows;
        }
    }
})
</script>
<!-- <script src="{{ asset( 'tendoo/js/components/modules.js' ) }}"></script> -->
<style>
/* @todo export this to the backend.css */
.modules-box {
    position: relative;
    top: -72px;
}
.module-actions .module-buttons {
    min-width: inherit;
}
</style>
@endpush