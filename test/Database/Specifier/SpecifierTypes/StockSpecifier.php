<?php
namespace FpDbTest\Database\Specifier\SpecifierTypes;

use FpDbTest\Database\Helper\Helper;
use FpDbTest\Database\Interfaces\SpecifierTypesInterface;

/**
 * SharpSpecifier - Класс обработки спецификатора ? (место вставки).
 */

class StockSpecifier extends SpecifierTypeAbstract implements SpecifierTypesInterface
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
        $this->value = Helper::defaultConvert($this->value);

        if (gettype($this->value) == "string") {
            $this->value = Helper::stringScreening($this->value);
        }

        return $this;
    }

}