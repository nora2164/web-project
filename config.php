<?php
/**
 * Database settings.
 *
 * Values are read from environment variables so no password is stored in the code.
 * The defaults match a fresh XAMPP / MAMP install (MySQL, user "root", empty password).
 *
 *   DB_DRIVER  mysql (default) or sqlite
 *   DB_HOST    default: localhost
 *   DB_NAME    default: booksummaries
 *   DB_USER    default: root
 *   DB_PASS    default: (empty)
 *   DB_PATH    SQLite file path, default: database/booksummaries.sqlite
 */

return [
    'driver' => getenv('DB_DRIVER') ?: 'mysql',
    'host'   => getenv('DB_HOST') ?: 'localhost',
    'name'   => getenv('DB_NAME') ?: 'booksummaries',
    'user'   => getenv('DB_USER') ?: 'root',
    'pass'   => getenv('DB_PASS') !== false ? getenv('DB_PASS') : '',
    'path'   => getenv('DB_PATH') ?: __DIR__ . '/database/booksummaries.sqlite',
];
