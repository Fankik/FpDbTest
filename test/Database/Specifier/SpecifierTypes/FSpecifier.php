<?php
namespace FpDbTest\Database\Specifier\SpecifierTypes;

use FpDbTest\Database\Helper\Helper;
use FpDbTest\Database\Interfaces\SpecifierTypesInterface;

/**
 * FSpecifier - Класс обработки спецификатора ?f.
 * Конвертация в число с плавающей точкой.
 */

class FSpecifier extends SpecifierTypeAbstract implements SpecifierTypesInterface
{
    /**
     * @var array $types Доступные типы значений.
     */

    protected array $types = [
        'string',
        'integer',
        'float',
        'double',
        'boolean',
        'NULL'
    ];

    /**
     * Метод обработки значения в зависимости от спецификатора.
     * 
     * @return self Возвращает свой контекст.
     */

    public function process(): self
    {
        $this->value = (float) $this->value;

        return $this;
    }
}