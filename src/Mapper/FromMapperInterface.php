<?php
/**
 * Механизм для миграции данных между БД с различными структурами
 *
 * @package Migrator
 * @author Roman Malashin <deller@inbox.ru>
 */


namespace Migrator\Mapper;


/**
 * Interface FromMapperInterface
 * @package Migrator\Mapper
 */
interface FromMapperInterface extends MapperInterface
{
    /**
     * Лимит данных, сколько забирать за партию.
     * @return integer
     */
    public function getLimit();

    /**
     * Устанавливает лимит данных, сколько забирать за партию.
     * @param int $limit
     * @return $this
     */
    public function setLimit($limit);

    /**
     *
     * @param int $offset
     * @return $this
     */
    public function setOffset($offset);

    /**
     * @return int
     */
    public function getOffset();
}