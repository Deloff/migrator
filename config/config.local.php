<?php
/**
 * Механизм для миграции данных между БД с различными структурами
 *
 * @package Migrator
 * @author Roman Malashin <deller@inbox.ru>
 */

return [
    'databases' => [
        'from' => [
            'driver' => 'pdo_pgsql',
            'host' => 'localhost',
            'port' => '5432',
            'user' => 'postgres',
            'password' => 'postgres',
            'dbname' => 'aip_old'
        ],
        'to' => [
            'driver' => 'pdo_pgsql',
            'host' => 'localhost',
            'port' => '5432',
            'user' => 'postgres',
            'password' => 'postgres',
            'dbname' => 'aip'
        ]
    ],
    'migrator' => [
        'data' => [
        ]
    ]
];