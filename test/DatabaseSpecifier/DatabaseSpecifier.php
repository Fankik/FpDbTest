<?php

namespace FpDbTest\DatabaseSpecifier;

use FpDbTest\Database\Database;
use FpDbTest\DatabaseInterfaces\DatabaseSpecifierInterface;
use FpDbTest\DatabaseSpecifierTypes\ASpecifier;
use FpDbTest\DatabaseSpecifierTypes\FSpecifier;
use FpDbTest\DatabaseSpecifierTypes\SharpSpecifier;
use FpDbTest\DatabaseSpecifierTypes\StockSpecifier;
use FpDbTest\DatabaseSpecifierTypes\DSpecifier;

use Exception;

/**
 * DatabaseSpecifier - Класс обработки места вствки и спецификаторов
 */

class DatabaseSpecifier implements DatabaseSpecifierInterface
{

    /**
     * @var array $specifiersClass Массив с классами типов спецификаторов
     */

    private array $specifiersClass = [];

    public function __construct()
    {
        $this->specifiersClass = [
            '?' => StockSpecifier::class,
            '?d' => DSpecifier::class,
            '?a' => ASpecifier::class,
            '?#' => SharpSpecifier::class,
            '?f' => FSpecifier::class,
        ];

    }

    /**
     * Метод получения обработанного запроса sql.
     * 
     * @param string $query Запрос sql.
     * @param array $args Массив значений для вставки.
     * @return string Возвращает запрос sql тип `string`.
     */

    public function getQuery(string $query, array $args): string
    {
        $specifiers = $this->getSpecifier($query);

        foreach ($specifiers as $key => $specifier) {
            if (!in_array($specifier, array_keys($this->specifiersClass))) {
                throw new Exception("Спецификатор $specifier не существует");
            }

            $specifiersArgs[] = [
                'specifier' => $specifier,
                'value' => $args[$key]
            ];
        }

        $specifiersValues = $this->setValues($specifiersArgs);
        $query = $this->fillQuery($query, $specifiersValues);

        return $query;
    }

    /**
     * Метод получения спецификаторов из запроса sql.
     * 
     * @param string $query Запрос sql.
     * @return array Массив спецификаторов.
     */

    private function getSpecifier(string $query): array
    {
        $pattern = "/\?[^\s]|\?/";
        preg_match_all($pattern, $query, $matches);

        return $matches[0];
    }

    /**
     * Метод получения класса типа спецификатора.
     * 
     * @param string $specifier Спецификатор.
     * @return string Класс типа спецификатора.
     */

    private function getSpecifierClass(string $specifier): string
    {
        if (isset($this->specifiersClass[$specifier])) {
            if (!empty($this->specifiersClass[$specifier]) && gettype($this->specifiersClass[$specifier]) == 'string') {
                return $this->specifiersClass[$specifier];
            } else {
                throw new Exception("Для спецификатора $specifier нет класса");
            }
        } else {
            throw new Exception("Спецификатор $specifier не существует");
        }
    }

    /**
     * Метод получения обработки значений в зависимости от спецификатора.
     * 
     * @param array $specifiers Спецификаторы и значения.
     * @return array Класс типа спецификатора.
     */

    private function setValues(array $specifiers): array
    {
        foreach ($specifiers as &$array) {
            $specifierClass = $this->getSpecifierClass($array['specifier']);
            $specifierType = new $specifierClass($array['value']);
            $array['value'] = $specifierType->get();
        }

        return $specifiers;
    }

    /**
     * Метод заполнения запроса `$query` обработанными значениями `$values`.
     * 
     * @param string $query Запрос sql.
     * @param array $values Обработанные значения.
     * @return string Заполненный запрос sql.
     */

    private function fillQuery(string $query, array $values): string
    {
        foreach ($values as $array) {
            $pos = strpos($query, $array['specifier']);
            if ($pos !== false) {
                $query = substr_replace($query, $array['value'], $pos, strlen($array['specifier']));
            }
        }

        return $query;
    }
}