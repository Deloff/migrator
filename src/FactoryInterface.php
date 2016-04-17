<?php
/**
 * Механизм для миграции данных между БД с различными структурами
 *
 * @package Migrator
 * @author Roman Malashin
 */
namespace Migrator;

/**
 * Interface FactoryInterface
 * @package Migrator
 */
interface FactoryInterface
{
    /**
     * @param array $specification
     * @return object
     */
    public function create(array $specification);
}
