const mix = require('laravel-mix');
const path = require('path');

// Configure the public path
mix.setPublicPath('public/build')
   .setResourceRoot('/build/');

// Compile JavaScript
mix.js('assets/app.js', 'js')
   .vue()
   .sourceMaps()
   .version();

// Compile CSS with PostCSS
mix.postCss('assets/styles/app.css', 'css', [
    require('postcss-import'),
    require('tailwindcss'),
    require('autoprefixer'),
]).version();

// Copy any additional assets
mix.copyDirectory('assets/images', 'public/build/images')
   .copy('node_modules/alpinejs/dist/cdn.js', 'public/build/js/alpine.js')
   .version();

// Configure webpack
mix.webpackConfig({
    resolve: {
        alias: {
            '@': path.resolve('assets/js'),
            '~': path.resolve('node_modules')
        }
    },
    output: {
        chunkFilename: 'js/[name].js?id=[chunkhash]',
        publicPath: '/build/'
    }
});

// Disable success notifications in development
if (!mix.inProduction()) {
    mix.disableSuccessNotifications();
}
