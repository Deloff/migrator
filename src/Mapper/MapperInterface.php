<?php
/**
 * Механизм для миграции данных между БД с различными структурами
 *
 * @package Migrator
 * @author Roman Malashin <deller@inbox.ru>
 */

namespace Migrator\Mapper;

use Doctrine\DBAL\Driver\Connection;


/**
 * Interface MapperInterface
 * @package Migrator\Mapper
 */
interface MapperInterface
{
    /**
     * @return Connection
     */
    public function getConnection();

    /**
     * @param Connection $connection
     * @return $this
     */
    public function setConnection(Connection $connection);
}
