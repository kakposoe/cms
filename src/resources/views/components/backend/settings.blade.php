@inject( 'Field', 'Tendoo\Core\Services\Field' )
@inject( 'Route', 'illuminate\Support\Facades\Route' )

@extends( 'tendoo::components.backend.master', [ 'parent_class' => 'p-0' ])
@section( 'tendoo::components.backend.master.body' )
<v-container fluid class="no-padding grey lighten-2" fill-height>
    <v-layout column>
        <v-flex d-block style="height: 160px;flex: none" class="primary pa-4">
            <v-layout column>
                <v-flex>
                    <v-layout row>
                        <v-flex class="white--text">
                            <span class="display-1">{{ __( 'Settings' )}}</span><br>
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
        <v-flex class="ma-4 settings-box">
            <div class="elevation-1 white">
                @include( 'tendoo::components.backend.settings.tabs.' . $tab )
            </div>
        </v-flex>
    </v-layout>
</v-container>
@endsection
@push( 'partials.shared.footer' )
<style>
.settings-box {
    position: relative;
    top: -78px;
}
</style>
@endpush