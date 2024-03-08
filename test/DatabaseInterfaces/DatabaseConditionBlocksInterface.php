<?php

namespace FpDbTest\DatabaseInterfaces;

/**
 * DatabaseConditionBlocksInterface - Интерфейс класса условных блоков.
 */

interface DatabaseConditionBlocksInterface
{
    /**
     * @param int $skip Переменная специального значения.
     */

    public function __construct(int $skip);

    /**
     * Метод получения обработанного запроса sql.
     * 
     * @param string $query Запрос sql.
     * @return string Возвращает запрос sql тип `string`.
     */

    public function getQuery(string $query): string;
}
