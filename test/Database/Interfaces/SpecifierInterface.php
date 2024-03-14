<?php

namespace FpDbTest\Database\Interfaces;

/**
 * SpecifierInterface - Интерфейс класса обработки места вставки и спецификаторов.
 */

interface SpecifierInterface
{
    /**
     * Метод получения обработанного запроса sql.
     * 
     * @param string $query Запрос sql.
     * @param array $args Массив значений для вставки.
     * @return string Возвращает запрос sql тип `string`.
     */

    public function getQuery(string $query, array $args): string;
}
