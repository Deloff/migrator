<?php
/**
 * Механизм для миграции данных между БД с различными структурами
 *
 * @package Migrator
 * @author Roman Malashin <deller@inbox.ru>
 */

use Cli\Helpers\Parameter;
use Cli\Helpers\DocumentedScript;
use Doctrine\DBAL\DriverManager;
use Doctrine\DBAL\Configuration;
use Migrator\Exception\RuntimeException;


$autoloadFiles = [
    __DIR__ . '/../vendor/autoload.php',
    __DIR__ . '/../../../autoload.php'
];

foreach ($autoloadFiles as $autoloadFile) {
    if (file_exists($autoloadFile)) {
        require_once $autoloadFile;
    }
}
try {
    $documentedScript = new DocumentedScript();
    $documentedScript->setName('migrator')
        ->setVersion('0.1')
        ->setDescription('Скрипт для миграции данных из одной БД в другую с различными структурами данных')
        ->setCopyright('Copyright (c) Roman Malashin 2016')
        ->addParameter(
            new Parameter('c', 'config', __DIR__ . '/../config/config.local.php'),
            'Путь до конфигурационного файла'
        )
        ->addParameter(new Parameter('t', 'type',  Parameter::VALUE_REQUIRED), 'Тип портируемых данных')
        ->setProgram(function ($options, $arguments) {
            $config = require_once __DIR__ . '/../config/config.local.php';

            if(!array_key_exists('migrator', $config) || !is_array($config['migrator'])) {
                throw new RuntimeException('В массиве конфигурации не задана секция migrator');
            }
            $migratorConfig = $config['migrator'];
            $fromConfigurationObject = new Configuration();
            $toConfigurationObject = clone $fromConfigurationObject;

            $fromConnection = DriverManager::getConnection($config['from'], $fromConfigurationObject);
            $toConnection = DriverManager::getConnection($config['to'], $toConfigurationObject);

            $migrator = new Migrator\Migrate($fromConnection, $toConnection, $migratorConfig);
            $migrator->run($arguments['type']);
        })
        ->start();
} catch (\Exception $e) {
    echo $e->getMessage();
}