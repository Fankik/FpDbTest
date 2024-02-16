<?php

namespace FpDbTest\DatabaseInterfaces;

/**
 * DatabaseSpecifierTypesInterface - Интерфейс класса типов спецификаторов
 */

interface DatabaseSpecifierTypesInterface
{
    public function __construct(mixed $value);

    /**
     * Метод возвращает обработанное значение в зависимости от спецификатора
     * 
     * @return mixed Обработанное значение
     */

    public function get(): mixed;
}
