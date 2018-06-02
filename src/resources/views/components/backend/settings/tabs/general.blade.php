@inject( 'Field', 'Tendoo\Core\Services\Field' )
@inject( 'Route', 'illuminate\Support\Facades\Route' )
<form action="{{ route( 'dashboard.options.post' ) }}" method="post">
    {{ csrf_field() }}
    {{ route_field() }}
    <v-layout column>
        <v-flex class="pa-3">
        {{ __( 'Application Details' ) }}
        </v-flex>
        <v-divider></v-divider>
        <v-flex class="pa-3">
            @each( 'tendoo::partials.shared.fields', $Field->generalSettings(), 'field' )
        </v-flex>
        <v-divider></v-divider>
        <v-flex class="pa-1">
            <v-btn type="submit" color="primary">{{ __( 'Save Settings' ) }}</v-btn>
        </v-flex>
    </v-layout>
</form>