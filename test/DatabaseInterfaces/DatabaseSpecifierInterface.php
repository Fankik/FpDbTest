<?php

namespace FpDbTest\DatabaseInterfaces;

/**
 * DatabaseSpecifierInterface - Интерфейс класса обработки места вствки и спецификаторов.
 */

interface DatabaseSpecifierInterface
{
    /**
     * Метод проверки, есть ли символ (?) для вставки.
     * Если в зпросе sql не найден символ (?) будет выброшен `Exception`.
     * 
     * @param string $query Запрос sql.
     * @return bool Возвращает `true` или `false`.
     */

    public function checkPlace(string $query): bool;

    /**
     * Метод проверки, количества мест вствки в запросе sql `$query` и количества передаваемых значений `$args`.
     * 
     * @param string $query Запрос sql.
     * @param array $args Массив значений для вставки.
     * @return bool Возвращает `true` или `false`.
     */

    public function checkPlacesAndArgs(string $query, array $args): bool;


    /**
     * Метод получения обработанного запроса sql.
     * 
     * @param string $query Запрос sql.
     * @param array $args Массив значений для вставки.
     * @return string Возвращает запрос sql тип `string`.
     */

    public function getQuery(string $query, array $args): string;
}
