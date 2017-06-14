<?php

/**
 * Slim Application Config
 */
return [
    'settings' => [
        'displayErrorDetails' => getenv('ENV') === 'development' ? true : false,
        'addContentLengthHeader' => false,
        'db' => [
            'host' => getenv('DB_HOST'),
            'name' => getenv('DB_NAME'),
            'user' => getenv('DB_USER'),
            'pass' => getenv('DB_PASS'),
        ]
    ]
];
