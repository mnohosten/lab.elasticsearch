<?php
declare(strict_types=1);

return [
    'app.debug' => true,

    'templates.cache_dir' => __DIR__ . '/../storage/cache/template',

    'translator.default.locale' => 'cs_CZ',
    'translator.cache_dir' => __DIR__ . '/../storage/cache/translator',
    'translator.resource_dir' => __DIR__ . '/../resources/translation',
    'translator.locales' => ['cs_CZ', 'en_US'],

    'component.map' => require __DIR__ . '/component.map.php',

    'console.commands' => require __DIR__ . '/console.commands.php',

    'pilulka.api.v1.storage_path' => __DIR__ . '/../storage/api.storage',
    'pilulka.api.v1.base_uri' => 'http://ipilulka.cz',
    'pilulka.api.v1.username' => 'pdp',
    'pilulka.api.v1.password' => 'a8b4Cjk$~',
    'pilulka.main_pharmacy_id' => 34,

];
