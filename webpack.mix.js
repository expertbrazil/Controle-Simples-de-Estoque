const mix = require('laravel-mix');

mix.js('resources/js/app.js', 'public/js')
   .js('resources/js/cep-search.js', 'public/js')
   .css('resources/css/custom.css', 'public/css')
   .version();

// Notificações de compilação
mix.disableNotifications();
