<?php

namespace FpDbTest\Database\Specifier\SpecifierTypes;

use FpDbTest\Database\Interfaces\SpecifierTypesInterface;
use Exception;

/**
 * SpecifierTypeAbstract - Абстрактный класс для описания методов 
 * используемых во всех дочерних классах
 */

abstract class SpecifierTypeAbstract implements SpecifierTypesInterface
{
    /**
     * @var array $types Доступные типы значений.
     */

    protected array $types = [];

    /**
     * @var mixed|array $value Обрабатываемое значение.
     */

    protected mixed $value = null;

    /**
     * Метод ставит значение.
     * 
     * @param mixed $name
     * @return self Возвращает свой контекст.
     */

    public function set(mixed $value): self
    {
        $type = gettype($value);

        if (in_array($type, $this->types)) {
            $this->value = $value;
        } else {
            throw new Exception("Тип $type не разрешен");
        }

        return $this;
    }

    /**
     * Метод возвращает значение.
     * 
     * @return string значение.
     */

    public function get(): ?string
    {
        return $this->value;
    }
}