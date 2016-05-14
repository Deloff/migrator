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
class AbstractMapper 
{
    use ConnectionTrait;

    public function __construct(Connection $connection = null)
    {
        if (is_object($connection)) {
            $this->setConnection($connection);
        }
    }
}