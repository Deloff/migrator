<?php
/**
 * @company MTE Telecom, Ltd.
 * @author Roman Malashin <malashinr@mte-telecom.ru>
 */

return [
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
];