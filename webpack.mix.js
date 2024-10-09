const mix = require('laravel-mix');
const path = require('path');
const { default: AutoImport } = require('unplugin-auto-import/webpack');
const { default: Components } = require('unplugin-vue-components/webpack');
const { ElementPlusResolver } = require('unplugin-vue-components/resolvers');

mix.webpackConfig({
    module: {
        rules: [{
            test: /\.mjs$/,
            resolve: { fullySpecified: false },
            include: /node_modules/,
            type: "javascript/auto"
        }]
    },
    stats: {
        children: true
    },
    plugins: [
        AutoImport({
            resolvers: [ElementPlusResolver()],
        }),
        Components({
            resolvers: [ElementPlusResolver()],
            directives: false
        }),
    ],
    resolve: {
        extensions: ['.js', '.vue', '.json'],
        alias: {
            '@': path.resolve(__dirname, 'resources')
        }
    }
});

mix
    .js('resources/admin/start.js', 'assets/admin/js/start.js').vue({ version: 3 })
    .js('resources/admin/global_admin.js', 'assets/admin/js/global_admin.js')
    .sass('resources/scss/admin.scss', 'assets/admin/css/admin.css')
    .copy('resources/images', 'assets/images')
    .copy('resources/fonts', 'assets/admin/css/fonts')
    .setPublicPath('assets');