<?php
/**
 * Механизм для миграции данных между БД с различными структурами
 *
 * @package Migrator
 * @author Roman Malashin
 */

namespace Migrator;

use ReflectionClass;

/**
 * Class AbstractFactory 
 * @package Migrator
 */
class AbstractFactory implements FactoryInterface
{
    /**
     * Создает экземпляр класса
     * @param array $specification
     * @return object
     */
    public function create(array $specification)
    {
        if (!array_key_exists('class', $specification)) {
            throw new Exception\RuntimeException(sprintf('Не задан класс'));
        }
        $reflection = new ReflectionClass($specification['class']);
        if (!$reflection->isInstantiable()) {
            throw new Exception\InvalidClassException(
                sprintf('Не могу создать экземпляр класса %s', $specification['class'])
            );
        }
        $options = array_key_exists('options', $specification) && $specification['options'] ?:null;
        if (!$options) {
            $class = $reflection->newInstance();
        } else {
            $class = $reflection->newInstance($options);
        }
        return $class;
    }
}
