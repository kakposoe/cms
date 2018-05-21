@verbatim
<app-toolbar inline-template>
    <v-toolbar
        color="blue darken-3"
        dark
        app
        :clipped-left="$vuetify.breakpoint.lgAndUp"
        fixed
        >
        <v-toolbar-title style="width: 250px" class="ml-0 pl-3">
            <v-toolbar-side-icon @click="toggleDrawer()"></v-toolbar-side-icon>
            <span class="hidden-sm-and-down">Ten<strong>doo</strong></span>
        </v-toolbar-title>
        <v-text-field
            flat
            solo-inverted
            prepend-icon="search"
            label="Search"
            class="hidden-sm-and-down"
        ></v-text-field>
        <v-spacer></v-spacer>
        <v-btn icon>
            <v-icon>apps</v-icon>
        </v-btn>
        <v-btn icon>
            <v-icon>notifications</v-icon>
        </v-btn>
    </v-toolbar>
</app-toolbar>
@endverbatim
@push( 'vue.components' )
<script>
Vue.component( 'app-toolbar', {
    mounted() {
        // console.log( OptionsData );
    },
    methods: {
        /**
         * Toogle Drawer
         * @return void
         */
        toggleDrawer() {
            OptionsData.drawer  =   ! OptionsData.drawer;
            TendooEvent.$emit( 'aside.toggle', OptionsData.drawer );
        }
    },
    data() {
        return {
            menus
        }
    }
});
</script>
@endpush