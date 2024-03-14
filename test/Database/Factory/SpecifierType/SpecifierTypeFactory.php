<?php

namespace FpDbTest\Database\Factory\SpecifierType;

use Exception;
use FpDbTest\Database\Interfaces\SpecifierTypesInterface;

/**
 * SpecifierTypeFactory - Фабрика для инициализации типов спецификаторов интерфейса `SpecifierTypesInterface`.
 */

class SpecifierTypeFactory
{
    /**
     * Метод инициализирует класс интерфейса `SpecifierTypesInterface`.
     * 
     * @param string $class Класс типа спецификатора.
     * @return SpecifierTypesInterface Инициализированный объект.
     */

    public static function make(string $class): SpecifierTypesInterface
    {
        if (class_exists($class)) {
            return new $class();
        } else {
            throw new Exception("Класса $class не существует");
        }
    }
}