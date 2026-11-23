<?php
// Copy this file to config.php and fill in your real values.
// config.php is listed in .gitignore and must never be committed.
return [
    'host'     => 'localhost',
    'port'     => 3306,
    'dbname'   => 'fitpulse',
    'user'     => 'your_db_user',
    'password' => 'your_db_password',
    'external_api' => [
        'base_url'        => 'https://wger.de/api/v2',
        'api_key'         => '',
        'connect_timeout' => 3,
        'timeout'         => 8,
    ],
];