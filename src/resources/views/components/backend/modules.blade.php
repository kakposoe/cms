@inject( 'Modules', 'Tendoo\Core\Services\Modules' ) 
@section( 'partials.shared.head' ) 
@extends( 'tendoo::components.backend.master', [ 'parent_class' => 'app-body-container' ] ) 
@push( 'vue.components' )
<script>
var data = {
    modules     :   @json( $Modules->get() )
}
</script>
<script src="{{ asset( 'tendoo/js/components/modules.js' ) }}"></script>
@endpush
@section( 'tendoo::components.backend.master.body' )
@php
$collection     =   collect( $Modules->get() );
$rowsModules    =   $collection->chunk(3);
@endphp
<v-container fluid fill-height grid-list-xl>
    <app-modules inline-template>
        <v-layout row fill-height :align-content-start="true">
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
    </app-modules>
</v-container>
@endsection