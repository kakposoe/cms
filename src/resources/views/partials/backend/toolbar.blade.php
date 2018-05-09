@verbatim
<v-toolbar
    color="blue darken-3"
    dark
    app
    :clipped-left="$vuetify.breakpoint.lgAndUp"
    fixed
    >
    <v-toolbar-title style="width: 250px" class="ml-0 pl-3">
        <v-toolbar-side-icon @click.stop="drawer = !drawer"></v-toolbar-side-icon>
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
@endverbatim