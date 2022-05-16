const mix = require('laravel-mix');
const tailwindcss = require('tailwindcss');
const path = require('path');

mix
    .setPublicPath('resources/dist')
    .ts('resources/js/architect.ts', '')
    .vue()
    .sass('resources/scss/architect.scss', '')
    .options({
        processCssUrls: false,
        postCss: [tailwindcss('tailwind.config.js')],
        uglify: {
            comments: false,
        },
    })
    // .webpackConfig({
    //     output: {
    //         chunkFilename: '[name].js?id=[chunkhash]',
    //     },
    // })
    .alias({
        '@': path.join(__dirname, 'resources/js'),
    })
    // .version()
    .sourceMaps();
