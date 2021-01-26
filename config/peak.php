<?php

declare(strict_types=1);

return [
    'composer' => [
        'key' => 'composer',
        'config' => 'composer.json',
        'require' => [],
        'require-dev' => [],
    ],

    'node' => [
        'key' => 'node',
        'config' => 'package.json',
        'dependencies' => [],
        'devDependencies' => [
            '@tailwindcss/aspect-ratio' => '^0.2.0',
            '@tailwindcss/forms' => '^0.2.1',
            '@tailwindcss/typography' => '^0.4.0',
            'alpinejs' => '^2.8.0',
            'autoprefixer' => '^10.2.3',
            'browser-sync' => '^2.26.13',
            'browser-sync-webpack-plugin' => '^2.3.0',
            'axios' => '^0.21.1',
            'laravel-mix' => '^6.0',
            'lodash' => '^4.17.20',
            'tailwindcss' => '^2.0.2'
        ],
    ],
];
