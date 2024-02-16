<?php

namespace FpDbTest\DatabaseConditionBlocks;

use Exception;
use FpDbTest\DatabaseInterfaces\DatabaseConditionBlocksInterface;

/**
 * DatabaseConditionBlocks - Класс обработки условных блоков.
 */

class DatabaseConditionBlocks implements DatabaseConditionBlocksInterface
{
    /**
     * @var int $skip Переменная специального значения.
     */

    private int $skip;

    /**
     * @param int $skip Переменная специального значения.
     */

    public function __construct(int $skip)
    {
        $this->skip = $skip;
    }

    /**
     * Метод проверяет присутствие условных болоков в заросе sql `$query`.
     * Если присутствует вложенный условный блок, то выбрасывается `Exception`.
     * 
     * @param string $query Запрос sql.
     * @return bool Возвращает `true` или `false`.
     */

    public function checkConditionalBlocks(string $query): bool
    {
        $result = false;

        $conditions = $this->extractCondition($query);

        foreach ($conditions as $condition) {
            if (!empty($this->extractCondition($condition))) {
                throw new Exception('Условные блоки не могут быть вложенными');
            }
        }

        if (!empty($condition)) {
            $result = true;
        }

        return $result;
    }

    /**
     * Метод получения обработанного запроса sql.
     * 
     * @param string $query Запрос sql.
     * @return string Возвращает запрос sql тип `string`.
     */

    public function getQuery(string $query): string
    {
        $conditions = $this->prepareConditions($query);
        $query = $this->fillQuery($query, $conditions);

        return $query;
    }

    /**
     * Метод подготовки условных блоков для заполнения запроса.
     * 
     * @param string $query Запрос sql.
     * @return array Массив условных блоков и значений для вставки.
     */

    private function prepareConditions(string $query): array
    {
        $result = [];
        $conditions = $this->extractCondition($query);

        foreach ($conditions as $condition) {

            $replace = '{' . $condition . '}';
            $to_replace = $condition;

            if (strpos($condition, (string) $this->skip) !== false) {
                $to_replace = '';
            }

            $result[] = [
                'replace' => $replace,
                'to_replace' => $to_replace
            ];
        }

        return $result;
    }

    /**
     * Метод заполнения запроса sql `$query` условными блоками.
     * 
     * @param string $query Запрос sql
     * @param array $conditions Массив условных блоков и значений для вставки
     * @return string Возвращает запрос sql тип `string
     */

    private function fillQuery(string $query, array $conditions): string
    {
        $search = [];
        $replace = [];

        foreach ($conditions as $condition) {
            $search[] = $condition['replace'];
            $replace[] = $condition['to_replace'];
        }

        $query = str_replace($search, $replace, $query);

        return $query;
    }

    /**
     * Метод для получения условных блоков в запросе sql `$query`.
     * 
     * @param string $query Запрос sql.
     * @return array Возвращает массив найденных условных блоков в запросе sql `$query`.
     */

    private function extractCondition(string $query): array
    {
        $result = [];
        $braceCount = 0;
        $currentText = '';

        for ($i = 0; $i < strlen($query); $i++) {
            if ($query[$i] == '{') {
                if ($braceCount > 0) {
                    $currentText .= '{';
                }
                $braceCount++;
            } elseif ($query[$i] == '}') {
                $braceCount--;
                if ($braceCount == 0) {
                    $result[] = $currentText;
                    $currentText = '';
                } else {
                    $currentText .= '}';
                }
            } elseif ($braceCount > 0) {
                $currentText .= $query[$i];
            }
        }

        return $result;
    }
}