@inject( 'Modules', 'Tendoo\Core\Services\Modules' )
@inject( 'Role', 'Tendoo\Core\Models\Role' )
@inject( 'User', 'Tendoo\Core\Models\User' )
@extends( 'tendoo::components.backend.master' )
@section( 'tendoo::components.backend.master.body' )
<v-container fluid class="no-padding" fill-height>
    <v-layout column class="page-header">
        <v-flex lg5 class="primary pa-4">
            <v-layout column>
                <v-flex>
                    <v-layout row>
                        <v-flex class="white--text">
                            <span class="display-1">Page Title</span><br>
                            <span>short description</span>
                        </v-flex>
                        <v-flex lg8 d-flex align-end>
                            <v-spacer></v-spacer>
                            <div style="flex: none !important;">
                                <div style="display: inline-block">
                                    <v-text-field solo label="First Name"></v-text-field>
                                </div>
                                <v-btn fab small dark color="success">
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
        <v-flex fill-height class="grey lighten-2">
            <v-layout>
                <v-flex class="ma-4">
                    <v-card d-flex height="100%" class="content-card">
                        <v-card-title>
                            Example
                        </v-card-title>
                        <v-divider></v-divider>
                        <v-card-text>
                            Content
                        </v-card-text>
                    </v-card>
                </v-flex>
            </v-layout>
        </v-flex>
    </v-layout>
</v-container>
@endsection