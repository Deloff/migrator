<?php
/**
 * Механизм для миграции данных между БД с различными структурами
 *
 * @package Migrator
 * @author Roman Malashin <deller@inbox.ru>
 */

namespace Migrator\Handler;

/**
 * Interface HandlerInterface
 * @package Migrator\Handler
 */
interface HandlerInterface
{
    /**
     * Обрабатывает данные, реализует бизнес логику и возвращает преобразованные данные
     * @param array $data
     * @return array
     */
    public function handle(array $data);
}
