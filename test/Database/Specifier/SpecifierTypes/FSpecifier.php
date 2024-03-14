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
        $type = gettype($this->value);

        $this->value = match ($type == 'NULL') {
            true => Helper::defaultConvert($this->value),
            false => (float) $this->value,
        };

        return $this;
    }
}