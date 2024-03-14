<?php

namespace FpDbTest\Database\Interfaces;

/**
 * SpecifierTypesInterface - Интерфейс класса типов спецификаторов
 */

interface SpecifierTypesInterface
{
    /**
     * Метод обработки значения в зависимости от спецификатора.
     * 
     * @return self Возвращает свой контекст.
     */

    public function process(): self;

    /**
     * Метод ставит значение.
     * 
     * @param mixed $name
     * @return self Возвращает свой контекст.
     */

    public function set(mixed $value): self;

    /**
     * Метод возвращает значение.
     * 
     * @return string значение.
     */

    public function get();
}
