const mix = require('laravel-mix');
const tailwindcss = require('tailwindcss');

mix.webpackConfig({
    watchOptions: { ignored: ['node_modules', 'vendor'] }
});

mix.js('resources/js/app.js', 'public/assets');
mix.sass('resources/sass/app.scss', 'public/assets');

mix.options({
    processCssUrls: false,
    postCss: [tailwindcss('tailwind.config.js')]
});

mix.copyDirectory(
    'node_modules/@fortawesome/fontawesome-free/webfonts',
    'public/assets/webfonts'
);

if (mix.inProduction()) {
    mix.version();
}

// mix.browserSync('ubergallery.local');
