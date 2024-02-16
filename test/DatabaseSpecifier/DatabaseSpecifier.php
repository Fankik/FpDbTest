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

    /**
     * @var array $defaultConvertValues Массив для замены значений
     */

    private array $defaultConvertValues = [
        true => 1,
        false => 0,
        null => 'NULL'
    ];

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

        $this->checkSpecifiers($specifiers);

        foreach ($specifiers as $key => $value) {
            $specifiersArgs[] = [
                'specifier' => $value,
                'value' => $args[$key]
            ];
        }

        $specifiersValues = $this->setValues($specifiersArgs);
        $query = $this->fillQuery($query, $specifiersValues);

        return $query;
    }

    /**
     * Метод проверки, есть ли символ (?) для вставки.
     * Если в зпросе sql не найден символ (?) будет выброшен `Exception`.
     * 
     * @param string $query Запрос sql.
     * @return bool Возвращает `true` или `false`.
     */

    public function checkPlace(string $query): bool
    {
        return strpos($query, '?') !== false;
    }

    /**
     * Метод проверки, количества мест вствки в запросе sql `$query` и количества передаваемых значений `$args`.
     * 
     * @param string $query Запрос sql.
     * @param array $args Массив значений для вставки.
     * @return bool Возвращает `true` или `false`.
     */

    public function checkPlacesAndArgs(string $query, array $args): bool
    {
        return substr_count($query, '?') == count($args);
    }

    /**
     * Метод для замены значений из массива `$specifiersClass`.
     * Если значения нет в массив, назад вернется передаваемое значение `$value`.
     * 
     * @param mixed $value Значение.
     * @return mixed Замененое значение | Передаваемое значение.
     */

    protected function defaultConvert(mixed $value): mixed
    {
        switch (gettype($value)) {
            case 'integer':
            case 'float':
            case 'double':
                return $value;
            default:
                if (isset($this->defaultConvertValues[$value])) {
                    return $this->defaultConvertValues[$value];
                } else {
                    return $value;
                }
        }
    }

    /**
     * Метод экранирования строки символом - `'`.
     * 
     * @param string $value Значение.
     * @return string Обработанное значение.
     */

    protected function stringScreening(string $value): string
    {
        if (substr_count($value, "'") != 2) {
            return "'" . $value . "'";
        } else {
            return $value;
        }
    }

    /**
     * Метод экранирования строки символом - `.
     * 
     * @param string $value Значение
     * @return string Обработанное значение
     */

    protected function stringApostrophe(string $value): string
    {
        if (substr_count($value, "`") != 2) {
            return "`" . $value . "`";
        } else {
            return $value;
        }
    }

    /**
     * Проверка спецификаторов в строке. Есть для них классы в массиве `$specifiersClass`.
     * 
     * @param array $specifiers Массив спецификаторов.
     * @return void
     */

    private function checkSpecifiers(array $specifiers): void
    {
        foreach ($specifiers as $specifier) {
            if (!in_array($specifier, array_keys($this->specifiersClass))) {
                throw new Exception("Спецификатор $specifier не существует");
            }
        }
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