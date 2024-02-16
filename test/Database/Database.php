<?php

namespace FpDbTest\Database;

use FpDbTest\DatabaseConditionBlocks\DatabaseConditionBlocks;
use FpDbTest\DatabaseInterfaces\DatabaseInterface;
use FpDbTest\DatabaseSpecifier\DatabaseSpecifier;

use Exception;
use mysqli;

/**
 * Database - Класс для построения запросов.
 */

class Database implements DatabaseInterface
{
    /**
     * @var mysqli $mysqli Объект mysqli.
     */

    private mysqli $mysqli;

    /**
     * @var DatabaseSpecifier $dbSpecifier Объект DatabaseSpecifier.
     */

    private DatabaseSpecifier $dbSpecifier;

    /**
     * @var DatabaseConditionBlocks $dbConditionBlocks Объект DatabaseConditionBlocks.
     */

    private DatabaseConditionBlocks $dbConditionBlocks;

    /**
     * @param mysqli $mysqli Объект mysqli.
     */

    public function __construct(mysqli $mysqli)
    {
        $this->mysqli = $mysqli;
        $this->dbSpecifier = new DatabaseSpecifier;
        $this->dbConditionBlocks = new DatabaseConditionBlocks($this->skip());
    }

    /**
     * Метод построения запроса.
     * Принимает запрос sql `$query` и значения для вставки в запрос sql `$args`.
     * 
     * @param string $query Запрос sql.
     * @param array $args Массив значений.
     * @return string Возвращает запрос sql тип `string`.
     */

    public function buildQuery(string $query, array $args = []): string
    {
        if (!$this->dbSpecifier->checkPlace($query)) {
            return $query;
        } else {
            if (!empty($args)) {
                if ($this->dbSpecifier->checkPlacesAndArgs($query, $args)) {
                    $query = $this->dbSpecifier->getQuery($query, $args);

                    if ($this->dbConditionBlocks->checkConditionalBlocks($query)) {
                        $query = $this->dbConditionBlocks->getQuery($query);
                    }

                    return $query;
                } else {
                    throw new Exception('Количество мест для вставки не совпадает с количеством передаваемых значений');
                }

            } else {
                throw new Exception('В запросе есть место для вставки, но значения не передаются');
            }
        }
    }

    /**
     * Метод для возврата специального значения.
     * Приминяется при обработке условного блока. Если в условном 
     * блоке есть специальное значение, то условный блок не попадает в запрос.
     *
     * @return int Возвращает специальное значение типа `int`.
     */

    public function skip(): int
    {
        return 0;
    }
}
