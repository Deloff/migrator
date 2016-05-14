<?php
/**
 *
 * @package aip.loc
 * @author Roman Malashin <deller@inbox.ru
 */

namespace Migrator\Mapper;

use Doctrine\DBAL\Driver\Connection;

/**
 * Class ConnectionTrait
 * @package Migrator\Mapper
 */
trait ConnectionTrait
{
    /**
     * @var Connection
     */
    protected $connection;

    /**
     * @return \Doctrine\DBAL\Driver\Connection
     */
    public function getConnection()
    {
        return $this->connection;
    }

    /**
     * @param \Doctrine\DBAL\Driver\Connection $connection
     * @return $this
     */
    public function setConnection(Connection $connection)
    {
        $this->connection = $connection;
        return $this;
    }
}