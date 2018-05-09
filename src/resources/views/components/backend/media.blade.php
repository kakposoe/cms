@inject( 'Field', 'Tendoo\Core\Services\Field' )
@inject( 'Route', 'illuminate\Support\Facades\Route' )
@extends( 'tendoo::components.backend.master', [ 'parent_class' => 'p-0' ])
@section( 'tendoo::components.backend.master.body' )
<v-container fluid fill-height grid-list-xl>
    <v-layout row fill-height :align-content-start="true">
        Medias
    </v-layout>
</v-container>
@endsection