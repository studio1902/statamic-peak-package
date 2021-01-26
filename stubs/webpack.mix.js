const mix = require('laravel-mix');

// mix.copyDirectory('resources/fonts', 'public/fonts')
//   .copyDirectory('resources/images', 'public/img');

mix.js('resources/js/app.js', 'public/js')
  .babel('public/js/app.js', 'public/js/app.es5.js')
  .sass('resources/sass/app.scss', 'public/css')
  .options({
      processCssUrls: false
  })
  .sourceMaps(false);

mix.browserSync({
    proxy: process.env.APP_URL,
    files: [
        'resources/views/**/*.html',
        'public/**/*.(css|js)',
    ],
    // Option to open in non default OS browser.
    // browser: "firefox",
    notify: false
});

mix.postCss('resources/css/tailwind.css', 'public/css/tailwind.css', [
    require('postcss-import'),
    require('tailwindcss'),
    require('postcss-nested'),
    require('postcss-focus-visible'),
    require('postcss-preset-env')({stage: 0}),
    require('autoprefixer'),
]);

if (mix.inProduction()) {
    mix.version();
}
