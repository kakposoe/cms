@inject( 'Modules', 'Tendoo\Core\Services\Modules' )
@inject( 'Role', 'Tendoo\Core\Models\Role' )
@inject( 'User', 'Tendoo\Core\Models\User' )
@inject( 'Event', 'Illuminate\Support\Facades\Event' )
@inject( 'Hook', 'Tendoo\Core\Facades\Hook' )
@inject( 'Request', 'Illuminate\Http\Request' )
@php
    $class      =   $Hook::filter( 'register.crud', $namespace );
@endphp

@if ( ! class_exists( $class ) )
    @include( 'tendoo::errors.unhandled-crud' )
@else
    @php
    $resource   =   new $class;
    @endphp

@extends( 'tendoo::components.backend.master' )
@section( 'tendoo::components.backend.master.body' )
<app-crud-table inline-template>
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

                                    <template v-if="searchStatus == 'open'">
                                        <div style="display: inline-block; width: 300px;">
                                            <v-text-field solo label="{{ __( 'Search' ) }}"></v-text-field>
                                        </div>
    
                                        <!-- When Open -->
                                        <v-btn @click="search()" fab small dark color="success">
                                            <v-icon>search</v-icon>
                                        </v-btn>
                                        <v-btn @click="toggleSearch()" fab small dark color="warning">
                                            <v-icon>close</v-icon>
                                        </v-btn>
                                    </template>

                                    <!-- When closed -->
                                    <v-btn v-if="searchStatus == 'closed'" @click="toggleSearch()" fab small dark color="success">
                                        <v-icon>search</v-icon>
                                    </v-btn>

                                    <v-btn fab small dark color="info">
                                        <v-icon>home</v-icon>
                                    </v-btn>
                                </div>
                            </v-flex>
                        </v-layout>
                    </v-flex>
                </v-layout>
            </v-flex>
            <v-flex fill-height class="grey lighten-3">
                <v-layout>
                    <v-flex class="ma-4 table-card">
                        <v-data-table
                            :headers="headers"
                            :items="items"
                            :search="search"
                            select-all
                            :pagination.sync="pagination"
                            :total-items="totalItems"
                            :loading="loading"
                            class="elevation-1"
                        >
                            <template slot="headers" slot-scope="props">
                                <tr>
                                    <th>
                                    <v-checkbox
                                        primary
                                        hide-details
                                        @click.native="toggleAll"
                                        :input-value="selectedNbr == itemsNbr"
                                        :indeterminate="(selectedNbr != itemsNbr) && selectedNbr > 0"
                                    ></v-checkbox>
                                    </th>
                                    <th
                                        v-for="header in props.headers"
                                        :key="header.text"
                                        :class="['column sortable', pagination.descending ? 'desc' : 'asc', header.value === pagination.sortBy ? 'active' : '']"
                                        @click="changeSort(header.value)"
                                        >
                                    <v-icon small>arrow_upward</v-icon>
                                    @{{ header.text }}
                                    @{{ selectedNbr }}
                                    </th>
                                </tr>
                            </template>
                            <template slot="items" slot-scope="props">
                                <tr
                                    :active="props.item.$selected" 
                                    @click="props.item.$selected = !props.item.$selected"
                                >
                                    <td>
                                        <v-checkbox
                                            primary
                                            hide-details
                                            :input-value="props.item.$selected"
                                        ></v-checkbox>
                                    </td>
                                    <td>@{{ props.item.username }}</td>
                                    <td class="text-xs-left">@{{ props.item.email }}</td>
                                    <td class="text-xs-left">@{{ props.item.active }}</td>
                                    <td class="text-xs-left">@{{ props.item.roles_name }}</td>
                                    <td class="text-xs-left">@{{ props.item.created_at }}</td>
                                </tr>
                            </template>
                        </v-data-table>
                    </v-flex>
                </v-layout>
            </v-flex>
        </v-layout>
    </v-container>
</app-crud-table>
@endsection
@push( 'vue.components' )
<script>
var data    =   {!! json_encode([
    'columns'       =>  $resource->getColumns(),
    'getURL'        =>  route( 'dashboard.crud.list', [
        'namespace' =>  $resource->getNamespace()
    ]),
    'deleteURL'     =>  route( 'dashboard.crud.delete', [
        'namespace' =>  $resource->getNamespace()
    ]),
    'editURL'       =>  route( 'dashboard.users.edit' ),
    'textDomain'    =>  [
        'deleteSelected'    =>  __( 'Would you like to delete selected entries ?' )
    ]
]) !!}
</script>
<script type="text/javascript">
    Vue.component( 'app-crud-table', {
        data() {
            return {
                searchStatus     :   'closed',
                loading     : false,
                totalItems  :   0,
                search      :   '',
                url         :   data.getUrl,
                pagination      :   {
                    sortBy  :   'username'
                },
                selected    :   [],
                items       :   [],
                headers     : [
                    { text: 'Username', value: 'username', align: 'right' },
                    { text: 'Email', value: 'email', align: 'right' },
                    { text: 'Role', value: 'roles_name', align: 'left' },
                    { text: 'Active', value: 'active', align: 'left' },
                    { text: 'Created', value: 'created_at', align: 'center' }
                ],
            }
        },
        watch: {
            pagination: {
                handler () {
                    this.getEntries();
                },
                deep: true
            }
        },
        computed : {
            selectedNbr() {
                return this.items.filter( item => item.$selected ).length;
            },
            itemsNbr() {
                return this.items.length;
            }
        },
        methods : {
            
            search() {
                
            },

            toggleAll() {
                this.items.forEach( item => {
                    item.$selected  =   ! item.$selected;
                });
            },

            changeSort( column ) {
                if (this.pagination.sortBy === column) {
                    this.pagination.descending = !this.pagination.descending
                } else {
                    this.pagination.sortBy = column
                    this.pagination.descending = false
                }
            },
            __serialize( object ) {
                var str = [];
                for (var p in object)
                    if (object.hasOwnProperty(p)) {
                    str.push(encodeURIComponent(p) + "=" + encodeURIComponent(object[p]));
                    }
                return str.join("&");
            },

            toggleSearch() {
                switch( this.searchStatus ) {
                    case 'closed': this.searchStatus = 'open'; break;
                    case 'open': this.searchStatus = 'closed'; break;
                    default: this.searchStatus = 'closed'; break; // we never know
                }

                this.getEntries();
            },
            getEntries() {
                this.loading    =   true;
                let pagination  =   Object.assign({}, this.pagination );
                // delete pagination.handler;
                axios.get( `${this.url}?${this.__serialize( pagination )}` ).then( request => {
                    // console.log( this.pagination );
                    // adding select field
                    request.data.data.forEach( entry => {
                        entry.$selected     =   false;
                    })
                    
                    // this.pagination.page            =   request.data.current_page;
                    // this.pagination.rowsPerPage     =   request.data.per_page;
                    this.loading                    =   false;
                    this.items                      =   request.data.data;
                    this.totalItems                 =   request.data.total;
                })
            }
        },
        mounted() {
        }
    })
</script>
@endpush
@section( 'partials.shared.footer' )
    @parent
    <script src="{{ asset( 'tendoo/js/dashboard/table.vue.js' ) }}"></script>
@endsection

@endif