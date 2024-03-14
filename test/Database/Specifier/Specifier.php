<?php

namespace FpDbTest\Database\Specifier;

use FpDbTest\Database\Factory\SpecifierType\SpecifierTypeFactory;
use FpDbTest\Database\Interfaces\SpecifierInterface;
use FpDbTest\Database\Specifier\SpecifierTypes\{ASpecifier, FSpecifier, SharpSpecifier, StockSpecifier, DSpecifier};

use Exception;

/**
 * Specifier - Класс обработки места вставки и спецификаторов
 */

class Specifier implements SpecifierInterface
{

    /**
     * @var array $specifierTypes Массив с классами типов спецификаторов
     */

    private array $specifierTypes = [];

    public function __construct()
    {
        $this->specifierTypes = [
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
        $specifiers = $this->getSpecifiers($query);

        $specifiersArgs = [];

        foreach ($specifiers as $key => $specifier) {
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

    private function getSpecifiers(string $query): array
    {
        $pattern = "/\?[^\s!{!}]|\?/";
        preg_match_all($pattern, $query, $matches);

        return $matches[0];
    }

    /**
     * Метод получения класса типа спецификатора.
     * 
     * @param string $specifier Спецификатор.
     * @return string Класс типа спецификатора.
     */

    private function getSpecifierTypesClass(string $specifier): string
    {
        if (isset($this->specifierTypes[$specifier])) {
            if (!empty($this->specifierTypes[$specifier]) && gettype($this->specifierTypes[$specifier]) == 'string') {
                return $this->specifierTypes[$specifier];
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

            ['specifier' => $specifier, 'value' => $value] = $array;

            $specifierTypesClass = $this->getSpecifierTypesClass($specifier);
            $specifierType = SpecifierTypeFactory::make($specifierTypesClass);

            $array['value'] = $specifierType->set($value)->process()->get();
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