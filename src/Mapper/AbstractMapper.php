<?php
/**
 *
 * @package aip.loc
 * @author Roman Malashin <deller@inbox.ru
 */

namespace Migrator\Mapper;

use Doctrine\DBAL\Driver\Connection;

/**
 * Class AbstractMapper
 * @package Migrator\Mapper
 */
abstract class AbstractMapper implements MapperInterface
{

    /**
     * @var Connection
     */
    protected $connection;

    /**
     * @param Connection $connection
     */
    public function __construct(Connection $connection)
    {
        $this->setConnection($connection);
    }

    /**
     * @return Connection | \Doctrine\DBAL\Connection
     */
    public function getConnection()
    {
        return $this->connection;
    }

    /**
     * @param Connection $connection
     * @return $this
     */
    public function setConnection(Connection $connection)
    {
        $this->connection = $connection;
        return $this;
    }
}