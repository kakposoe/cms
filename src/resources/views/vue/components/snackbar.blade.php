@push( 'vue.footer' )
<app-snackbar inline-template>
    <v-snackbar
        :top="y === 'top'"
        :bottom="y === 'bottom'"
        :right="x === 'right'"
        :left="x === 'left'"
        :vertical="mode === 'vertical'"
        v-model="snackbar"
        :color="color"
        >
        @{{ text }}
        <v-btn flat color="pink" @click.native="snackbar = false">Close</v-btn>
    </v-snackbar>
</app-snackbar>
@endpush
@push( 'vue.components' )
<script type="text/javascript">
Vue.component( 'app-snackbar', {
    data() {
        return {
            y: 'bottom',
            x: 'middle',
            mode: 'multi-line',
            snackbar: false,
            text: '',
            color: 'info'
        }
    }, 
    mounted() {
        TendooEvent.$on( 'show.snackbar', ( config ) => {
            this.snackbar   =   true;
            this.text       =   config.message;

            switch( config.status ) {
                case 'danger' : this.color = 'error'; break;
                case 'success' : this.color = 'success'; break;
            }
        });
    }
})
</script>
@endpush