const mix = require('laravel-mix');

mix.js('resources/js/app.js', 'public/back/js') // Compile JS files
    .postCss('resources/css/app.css', 'public/back/css', [ // Compile CSS files
        // You can add PostCSS plugins or options here if needed
    ]);
