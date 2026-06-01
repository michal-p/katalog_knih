<?php

return [
    // getenv() reads the value from the server environment (injected by Docker from our .env file)
    'host' => getenv('DB_HOST') ?: 'db',
    'dbname' => getenv('DB_DATABASE') ?: 'ebooks',
    'user' => getenv('DB_USERNAME') ?: 'app',
    'password' => getenv('DB_PASSWORD') ?: 'apppass',
    'charset' => 'utf8mb4'
];
