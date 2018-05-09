<v-content>
    <v-container fluid fill-height>
        <v-layout justify-center align-center>
        <v-tooltip right>
            <v-btn
            icon
            large
            :href="source"
            target="_blank"
            slot="activator"
            >
            <v-icon large>code</v-icon>
            </v-btn>
            <span>Source</span>
        </v-tooltip>
        <v-tooltip right>
            <v-btn icon large href="https://codepen.io/johnjleider/pen/EQOYVV" target="_blank" slot="activator">
            <v-icon large>mdi-codepen</v-icon>
            </v-btn>
            <span>Codepen</span>
        </v-tooltip>
        </v-layout>
    </v-container>
</v-content>