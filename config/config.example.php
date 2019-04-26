<?php
declare(strict_types=1);

return [
    'app.debug' => true,

    'templates.cache_dir' => __DIR__ . '/../storage/cache/template',

    'translator.default.locale' => 'cs_CZ',
    'translator.cache_dir' => __DIR__ . '/../storage/cache/translator',
    'translator.resource_dir' => __DIR__ . '/../resources/translation',
    'translator.locales' => ['cs_CZ', 'en_US'],

    'console.commands' => require __DIR__ . '/console.commands.php',

    'pilulka.api.v1.storage_path' => __DIR__ . '/../storage/api.storage',
];
