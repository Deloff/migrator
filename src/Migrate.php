<?php
/**
 * @company MTE Telecom, Ltd.
 * @author Roman Malashin <malashinr@mte-telecom.ru>
 */

namespace Migrator;

use Doctrine\DBAL\Driver\Connection;

/**
 * Class Migrate 
 * @package Migrator
 */
class Migrate 
{
    /**
     * Соединения с БД из которой забираются данные
     * @var Connection
     */
    protected $fromConnection;

    /**
     * Соединения с бд в которую записываются данны
     * @var Connection
     */
    protected $toConnection;

    /**
     * Опции для механизма миграций
     * @var array
     */
    protected $options;

    /**
     * @param array $config
     * @param array $options
     */
    public function __construct(Connection $fromConnection, Connection $toConnection, array $options = [])
    {
        $this->setFromConnection($fromConnection);
        $this->setToConnection($toConnection);
        $this->setOptions($options);
    }

    /**
     *
     * @param array $configuration
     */
    public function configure($configuration)
    {

    }



    public function run()
    {

    }

    /**
     * @return array
     */
    public function getOptions()
    {
        return $this->options;
    }

    /**
     * @param array $options
     * @return $this
     */
    public function setOptions($options)
    {
        $this->options = $options;
        return $this;
    }

    /**
     * @return \Doctrine\DBAL\Driver\Connection
     */
    public function getFromConnection()
    {
        return $this->fromConnection;
    }

    /**
     * @param \Doctrine\DBAL\Driver\Connection $fromConnection
     * @return $this
     */
    public function setFromConnection($fromConnection)
    {
        $this->fromConnection = $fromConnection;
        return $this;
    }

    /**
     * @return \Doctrine\DBAL\Driver\Connection
     */
    public function getToConnection()
    {
        return $this->toConnection;
    }

    /**
     * @param \Doctrine\DBAL\Driver\Connection $toConnection
     * @return $this
     */
    public function setToConnection($toConnection)
    {
        $this->toConnection = $toConnection;
        return $this;
    }
}