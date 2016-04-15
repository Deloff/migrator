<?php
/**
 * @company MTE Telecom, Ltd.
 * @author Roman Malashin <malashinr@mte-telecom.ru>
 */

use Cli\Helpers\Parameter;
use Cli\Helpers\DocumentedScript;
use Doctrine\DBAL\DriverManager;
use Doctrine\DBAL\Configuration;


$autoloadFiles = array(__DIR__ . '/../vendor/autoload.php',
    __DIR__ . '/../../../autoload.php');

foreach ($autoloadFiles as $autoloadFile) {
    if (file_exists($autoloadFile)) {
        require_once $autoloadFile;
    }
}
try {
    $config = require_once __DIR__ . '/../config/config.local.php';

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
        ->addParameter(new Parameter('v', 'verbose', Parameter::VALUE_NO_VALUE), 'Enable verbosity.')
        ->setProgram(function ($options, $arguments) {
            var_dump($arguments);
            var_dump($options);
        })
        ->start();


    $fromConfigurationObject = new Configuration();
    $toConfigurationObject = clone $fromConfigurationObject;

    $fromConnection = DriverManager::getConnection($config['from'], $fromConfigurationObject);
    $toConnection = DriverManager::getConnection($config['to'], $toConfigurationObject);

//    $migrator = new Migrator\Migrate($fromConnection, $toConnection);
//    $migrator->run();
} catch (\Exception $e) {
    echo $e->getMessage();
}