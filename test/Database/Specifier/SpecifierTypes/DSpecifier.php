<?php
namespace FpDbTest\Database\Specifier\SpecifierTypes;

use FpDbTest\Database\Helper\Helper;
use FpDbTest\Database\Interfaces\SpecifierTypesInterface;

/**
 * DSpecifier - Класс обработки спецификатора ?d.
 * Конвертация в целое число.
 */

class DSpecifier extends SpecifierTypeAbstract implements SpecifierTypesInterface
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
        $type = gettype($this->value);

        $this->value = match ($type == 'NULL') {
            true => Helper::defaultConvert($this->value),
            false => (int) $this->value,
        };

        return $this;
    }
}