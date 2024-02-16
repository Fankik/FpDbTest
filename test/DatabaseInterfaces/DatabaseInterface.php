<?php

namespace FpDbTest\DatabaseInterfaces;

/**
 * DatabaseInterface - Интерфейс класса для построения запросов.
 */
interface DatabaseInterface
{
    /**
     * Метод построения запроса.
     * Принимает запрос sql `$query` и значения для вставки в запрос sql `$args`.
     * 
     * @param string $query Запрос sql.
     * @param array $args Массив значений.
     * @return string Возвращает запрос sql тип `string`.
     */

    public function buildQuery(string $query, array $args = []): string;

    /**
     * Метод для возврата специального значения.
     * Приминяется при обработке условного блока. Если в условном 
     * блоке есть специальное значение, то условный блок не попадает в запрос.
     *
     * @return int Возвращает специальное значение типа `int`.
     */

    public function skip(): int;
}
