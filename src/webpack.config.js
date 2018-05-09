var webpack     =   require( 'webpack' );
const path  =   require( 'path' );

module.exports = {
    entry: {
        app:  "./public/main.js",
        vendor: [ 'vue', 'axios', 'vuetify' ]
    },
    output: {
        path: path.resolve( __dirname, 'public/js' ),
        filename: 'tendoo.[name].js',
        publicPath: './public'
    },
    resolve: {
        alias: {
            'vue$': 'vue/dist/vue.esm.js' // 'vue/dist/vue.common.js' for webpack 1
        }
    },
    plugins: [
        new webpack.optimize.MinChunkSizePlugin({
            name : 'vendor'
        })
    ]
}

if( process.env.NODE_ENV === 'production' ) {
    module.exports.plugins.push(
        new webpack.optimize.UglifyJsPlugin({
            sourceMap: true,
            compress: {
                warnings: false
            }
        })
    )
}