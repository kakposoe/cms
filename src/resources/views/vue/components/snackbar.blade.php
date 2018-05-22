@push( 'vue.footer' )
<app-snackbar inline-template>
    <v-snackbar
        :top="sb1.y === 'top'"
        :bottom="sb1.y === 'bottom'"
        :right="sb1.x === 'right'"
        :left="sb1.x === 'left'"
        :vertical="sb1.mode === 'vertical'"
        v-model="sb1.snackbar"
        :color="sb1.color"
        >
        @{{ sb1.text }}
        <v-btn flat color="pink" @click.native="sb1.snackbar = false">Close</v-btn>
    </v-snackbar>
    <v-snackbar
        :top="sb2.y === 'top'"
        :bottom="sb2.y === 'bottom'"
        :right="sb2.x === 'right'"
        :left="sb2.x === 'left'"
        :vertical="sb2.mode === 'vertical'"
        v-model="sb2.snackbar"
        :color="sb2.color"
        >
        @{{ sb1.text }}
        <v-btn flat color="pink" @click.native="sb2.snackbar = false">Close</v-btn>
    </v-snackbar>
</app-snackbar>
@endpush
@push( 'vue.components' )
<script type="text/javascript">
@php
    $errorsArray    =   [];

    if ( @$errors->has( 'status' ) ) {
        $errorsArray[]  =   [
            'message'   =>  $errors->first( 'message' ),
            'status'    =>  $errors->first( 'status' )
        ];
    } else {
        foreach([ 'success', 'danger', 'info' ] as $status ) {
            if ( session()->get( 'status' ) === $status ) {
                $errorsArray[]  =   [
                    'message'   =>  session()->get( 'message' ),
                    'status'    =>  $status
                ];
            }
        }
    }
@endphp
var SnackBarDatas    =   {
    notices          :   @json( $errorsArray )
};

Vue.component( 'app-snackbar', {
    data() {
        return Object.assign({}, SnackBarDatas, {
            sb1     :   {
                y: 'bottom',
                x: 'middle',
                mode: 'multi-line',
                snackbar: false,
            },
            sb2     :   {
                y: 'bottom',
                x: 'middle',
                mode: 'multi-line',
                snackbar: false,
            },
            text: '',
            color: 'info'
        })
    }, 
    mounted() {
        TendooEvent.$on( 'show.snackbar', ( config ) => {
            this.sb1.snackbar   =   true;
            this.sb1.text       =   config.message;

            switch( config.status ) {
                case 'danger' : this.sb1.color = 'error'; break;
                case 'success' : this.sb1.color = 'success'; break;
            }
        });
        
        // let's render automatically session notification
        this.notices.forEach( notice => {
            this.sb1.snackbar   =   true;
            this.sb1.text       =   notice.message.replace(/(<([^>]+)>)/ig,"");
            switch( notice.status ) {
                case 'danger' : notice.status = 'error'; break;
                case 'success' : notice.status = 'success'; break;
            }
        });
    }
})
</script>
@endpush