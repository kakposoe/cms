@inject( 'Hook', 'Tendoo\Core\Facades\Hook' )
@php
    $class      =   $Hook::filter( 'register.crud', $namespace );
@endphp

@if ( ! class_exists( $class ) )
    @include( 'tendoo::errors.unhandled-crud' )
@else
    @php
    $resource   =   new $class;
    @endphp
    <app-crud-form inline-template>
        <v-container fluid class="no-padding" fill-height>
            <v-layout column class="page-header">
                <v-flex style="height: 180px" class="primary pa-4">
                    <v-layout column>
                        <v-flex>
                            <v-layout row>
                                <v-flex class="white--text">
                                    <span class="display-1">{{ @$title }}</span><br>
                                    <span>{{ @$description }}</span>
                                </v-flex>
                                <v-flex lg8 d-flex align-end>
                                <v-spacer></v-spacer>
                                <div style="flex: none !important;">
                                    @foreach( $resource->getLinks() as $screen => $links )
                                        @if ( $screen == 'create' ) 
                                            @foreach( $links as $link )
                                                <v-btn fab href="{{ $link[ 'href' ] }}" small dark color="info">
                                                    <v-icon>{{ @$link[ 'icon' ] ? $link[ 'icon' ] : 'star' }}</v-icon>
                                                </v-btn>
                                            @endforeach
                                        @endif
                                    @endforeach
                                </div>
                            </v-flex>
                            </v-layout>
                        </v-flex>
                    </v-layout>
                </v-flex>
                <v-flex fill-height class="grey lighten-3">
                    <v-layout>
                        <v-flex class="ma-4 table-card">
                            <v-card>
                                <v-card-title>
                                    <h3>{{ $resource->edit_title }}</h3>
                                </v-card-title>
                                <v-divider></v-divider>
                                <v-card-text>
                                    <v-form>
                                        <template v-for="field in formFields">

                                            <!-- if fields is a text -->
                                            <v-text-field
                                                v-if="field.type == 'text'"
                                                :label="field.label"
                                                :error="field.error"
                                                :hint="field.description"
                                                v-model="models[ field.name ]"
                                                :rules="field.errors"
                                            ></v-text-field>
                                            <!-- end text fields -->
                                            
                                            <!-- if fields is a text -->
                                            <v-text-field
                                                v-if="field.type == 'password'"
                                                :label="field.label"
                                                :error="field.error"
                                                :hint="field.description"
                                                type="password"
                                                :rules="field.errors"
                                                v-model="models[ field.name ]"
                                            ></v-text-field>
                                            <!-- end text fields -->

                                            <!-- options for v-select -->
                                            <v-select
                                                v-if="field.type == 'select'"
                                                :items="field.items"
                                                :error="field.error"
                                                :hint="field.description"
                                                v-model="models[ field.name ]"
                                                :label="field.label"
                                                :rules="field.errors"
                                                single-line
                                            ></v-select>
                                            <!-- end of v-select -->

                                        </template>
                                    </v-form>
                                </v-card-text>
                                <v-divider></v-divider>
                                <v-card-actions>
                                    <v-btn @click="proceed( 'save-and-return' )" color="primary">{{ __( 'Save & Return' ) }}</v-btn>
                                    <v-btn @click="proceed( 'save' )" color="primary">{{ __( 'Save' ) }}</v-btn>
                                    <v-btn @click="proceed( 'return' )" color="warn">{{ __( 'Return' ) }}</v-btn>
                                </v-card-actions>
                            </v-card>
                        </v-flex>
                    </v-layout>
                </v-flex>
            </v-layout>
        </v-container>
    </app-crud-form>
@endif
@push( 'vue.components' )
<script>
var data        =   new Object;
data.fields     =   {!! json_encode( $resource->getFields() ) !!};
data.url        =   {
    list    :   '{{ route( $resource->get( "main-route" ) ) }}',
    put     :   '{{ route( "dashboard.crud.put", [  "namespace"     =>  $resource->get( "namespace" ), "id" => $entry->id ] ) }}'
}
data.entry      =   {!! json_encode( $entry ) !!};

Vue.component( 'app-crud-form', {
    data() {
       return Object.assign({}, data, {
           models   :   new Object
       }) 
    }, 
    
    watch: {
        fields() {
            console.log( 'ok' );
        }
    },
     
    methods : {
        buildModels() {
            this.fields.forEach( field => {
                // update the model value
                this.models[ field.name ]    =   this.entry[ field.name ];
                
                // Building a new options for v-select
                if ( field.type == 'select' ) {
                    let keys    = Object.keys( field.options );
                    let values  = Object.values( field.options );
                    let options  =  [];
                    
                    keys.forEach( ( key, index ) => {
                        
                        let optionValue     =   {
                            text : values[ index ],
                            value: key
                        };

                        options.push( optionValue );

                        // for select input, we might need to loop 
                        // the created oject and assign it as a selected value
                        if ( key == this.entry[ field.name ] ) {
                            this.models[ field.name ]   =   options[ index ];
                        }
                    });
                    field.items   =   options;
                }

                // Prepare the error array for the item
                field.error     =   false;
                field.errors    =   [];
            });
        },

        treatErrorResponse( result ) {
            TendooEvent.$emit( 'show.snackbar', { message : result.message, status : 'danger' });
            let fields  = Object.keys( result.errors );
            this.fields.forEach( ( field, index ) => {
                if ( fields.indexOf( field.name ) != -1 ) {
                    Vue.set( this.fields, index, Object.assign({}, this.fields[ index ], {
                        error   :   true,
                        errors  :   result.errors[ field.name ].map( error => () => error )
                    }));
                } else {
                    // Let's remove the error if the fields is correctly filled
                    Vue.set( this.fields, index, Object.assign({}, this.fields[ index ], {
                        error   :   false,
                        errors  :   []
                    }));
                }
            });
        },

        proceed( action ) {
            // during the editing, we've made a modification, which applied 
            // an object as a value. We should reverse it then
            this.__destructureModels();

            // Go to the main list
            if ( action == 'return' ) {
                return this.__return();
            } else if ( action == 'save-and-return' ) {
                axios.post( `${this.url.put}`, this.models ).then( result => {
                    TendooEvent.$emit( 'show.snackbar',  result.data );
                    setTimeout( () => this.__return(), 1000 );
                }).catch( error => {
                    this.treatErrorResponse( error.response.data );
                });
            } else if ( action === 'save' ) {
                axios.post( `${this.url.put}`, this.models ).then( result => {
                    TendooEvent.$emit( 'show.snackbar',  result.data );
                }).catch( error => {
                    this.treatErrorResponse( error.response.data );
                });
            }
        },

        __destructureModels() {
            this.fields.forEach( field => {
                if ( field.type == 'select' && typeof this.models[ field.name ] == 'object' ) {
                    this.models[ field.name ]   =   this.models[ field.name ].value;
                }
            });
        },

        __return() {
            document.location   =   this.url.list;
        }
    },
    created() {
        this.buildModels();
    },
    computed : {
        formFields() {
            console.log( this.$v );
            return this.fields;
        }
    } 
})
</script>
@endpush