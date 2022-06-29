const mix = require('laravel-mix');
const tailwindcss = require('tailwindcss');
const path = require('path');

mix
    .setPublicPath('resources/dist')
    .ts('resources/ts/architect.ts', '')
    .vue()
    .sass('resources/scss/architect.scss', '')
    .options({
        processCssUrls: false,
        postCss: [tailwindcss('tailwind.config.js')],
        uglify: {
            comments: false,
        },
    })
    .alias({
        '@': path.join(__dirname, 'resources/ts'),
    })
    // .version()
    .sourceMaps();
