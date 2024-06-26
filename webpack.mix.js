const mix = require('laravel-mix');
const tailwindcss = require('tailwindcss');

mix.js('resources/js/app.js', 'public/js')
    .postcss('resources/css/app.css', 'public/css', [
        require('postcss-import'),
        tailwindcss('./tailwind.config.js'),
    ]);
