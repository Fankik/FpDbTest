<?php

namespace FpDbTest\DatabaseInterfaces;

/**
 * DatabaseSpecifierInterface - Интерфейс класса обработки места вствки и спецификаторов.
 */

interface DatabaseSpecifierInterface
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
