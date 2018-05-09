Vue.component( 'app-modules', {
    data() {
        return Object.assign({}, {
            modules : []
        }, data );
    },
    methods: {
        delete( module ) {
            
        }
    },
    mounted() {
        console.log( this.modules );
    }
})