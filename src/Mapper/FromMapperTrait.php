<?php
/**
 *
 * @package aip.loc
 * @author Roman Malashin <deller@inbox.ru
 */

namespace Migrator\Mapper;


trait FromMapperTrait
{
    /**
     * @var int
     */
    protected $limit = 50;

    /**
     * @var int
     */
    protected $offset = 0;

    /**
     * @return int
     */
    public function getLimit()
    {
        return $this->limit;
    }

    /**
     * @param int $limit
     * @return $this
     */
    public function setLimit($limit)
    {
        $this->limit = $limit;
        return $this;
    }

    /**
     * @return int
     */
    public function getOffset()
    {
        return $this->offset;
    }

    /**
     * @param int $offset
     * @return $this
     */
    public function setOffset($offset)
    {
        $this->offset = $offset;
        return $this;
    }
}