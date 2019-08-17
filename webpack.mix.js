const mix = require('laravel-mix');

mix.react('resources/js/maelstrom.js', 'public/js');

mix.postCss('resources/sass/maelstrom.css', 'public/css', [
    require('postcss-import'),
    require('tailwindcss'),
]);

mix.webpackConfig({
    module: {
        rules: [require('@maelstrom-cms/toolkit/js/support/DontIgnoreMaelstrom')()],
    },
});
