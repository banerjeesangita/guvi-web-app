<?php
return [
    'debug' => true,

    'App' => [
        'namespace' => 'App',
        'encoding' => 'UTF-8',
        'defaultLocale' => 'en_US',
        'defaultTimezone' => 'UTC',
        'base' => false,
        'dir' => 'src',
        'webroot' => 'webroot',
        'wwwRoot' => WWW_ROOT,
        'fullBaseUrl' => 'http://localhost:8765',
        'imageBaseUrl' => 'img/',
        'cssBaseUrl' => 'css/',
        'jsBaseUrl' => 'js/',
        'paths' => [
            'templates' => [ROOT . DS . 'templates' . DS],
        ],
    ],

    'Datasources' => [
        'default' => [
            'className' => \Cake\Database\Connection::class,
            'driver' => \Cake\Database\Driver\Mysql::class,
            'persistent' => false,
            'host' => 'localhost',
            'username' => 'root',
            'password' => 'demo',
            'database' => 'demo',
            'encoding' => 'utf8mb4',
            'timezone' => 'UTC',
            'cacheMetadata' => true,
            'quoteIdentifiers' => false,
            'log' => false,
            'url' => env('DATABASE_URL', null),
        ],
    ],

    'Mongo' => [
        'uri' => env('MONGO_URI', 'mongodb://localhost:27017'),
        'database' => 'cake_auth_demo',
        'profilesCollection' => 'user_profiles',
    ],

    'Redis' => [
        'host' => env('REDIS_HOST', '127.0.0.1'),
        'port' => env('REDIS_PORT', 6379),
        'password' => env('REDIS_PASSWORD', null),
        'tokenTtl' => 3600,
        'keyPrefix' => 'auth_token:',
    ],

    'Security' => [
        'salt' => env('SECURITY_SALT', 'CHANGE-THIS-TO-A-LONG-RANDOM-STRING'),
    ],

    'Error' => [
        'errorLevel' => E_ALL,
        'skipLog' => [],
        'log' => true,
        'trace' => true,
    ],

    'Cache' => [
        'default' => [
            'className' => 'File',
            'path' => CACHE,
            'url' => env('CACHE_DEFAULT_URL', null),
        ],
        '_cake_translations_' => [
            'className' => 'File',
            'path' => CACHE . 'persistent' . DS,
            'prefix' => 'guvi_web_cake_translations_',
            'serialize' => true,
            'duration' => '+1 years',
            'url' => env('CACHE_CAKE_TRANSLATIONS_URL', null),
        ],
        '_cake_model_' => [
            'className' => 'File',
            'path' => CACHE . 'models' . DS,
            'prefix' => 'guvi_web_cake_model_',
            'serialize' => true,
            'duration' => '+1 years',
            'url' => env('CACHE_CAKE_MODEL_URL', null),
        ],
    ],

    'EmailTransport' => [
        'default' => [
            'className' => \Cake\Mailer\Transport\MailTransport::class,
        ],
    ],

    'Email' => [
        'default' => [
            'transport' => 'default',
            'from' => 'noreply@example.com',
        ],
    ],

    'Log' => [
        'debug' => [
            'className' => \Cake\Log\Engine\FileLog::class,
            'path' => LOGS,
            'file' => 'debug',
            'url' => env('LOG_DEBUG_URL', null),
            'scopes' => null,
            'levels' => ['notice', 'info', 'debug'],
        ],
        'error' => [
            'className' => \Cake\Log\Engine\FileLog::class,
            'path' => LOGS,
            'file' => 'error',
            'url' => env('LOG_ERROR_URL', null),
            'scopes' => null,
            'levels' => ['warning', 'error', 'critical', 'alert', 'emergency'],
        ],
    ],
];
