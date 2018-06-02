@inject( 'Field', 'Tendoo\Core\Services\Field' )
@inject( 'Route', 'illuminate\Support\Facades\Route' )
@inject( 'Helper', 'Tendoo\Core\Services\Helper' )
<form action="{{ route( 'dashboard.options.post' ) }}" method="post">
    {{ csrf_field() }}
    {{ route_field() }}
    <v-layout column>
        <v-flex class="pa-3">
        {{ __( 'Application Details' ) }}
        </v-flex>
        <v-divider></v-divider>
        <v-flex class="pa-3">
            <v-layout row>
                <v-flex class="mr-1">
                @each( 'tendoo::partials.shared.fields', $Helper::arrayDivide( $Field->registration(), 'even' ), 'field' )
                </v-flex>
                <v-flex class="ml-1">
                @each( 'tendoo::partials.shared.fields', $Helper::arrayDivide( $Field->registration(), 'odd' ), 'field' )
                </v-flex>
            </v-layout>
        </v-flex>
        <v-divider></v-divider>
        <v-flex class="pa-1">
            <v-btn type="submit" color="primary">{{ __( 'Save Settings' ) }}</v-btn>
        </v-flex>
    </v-layout>
</form>