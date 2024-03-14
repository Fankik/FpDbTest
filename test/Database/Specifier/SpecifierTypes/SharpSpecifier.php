<?php
namespace FpDbTest\Database\Specifier\SpecifierTypes;

use FpDbTest\Database\Helper\Helper;
use FpDbTest\Database\Interfaces\SpecifierTypesInterface;

use Exception;

/**
 * SharpSpecifier - Класс обработки спецификатора ?#.
 * Массив значений или значение. Значение преобразуется в список. 
 */

class SharpSpecifier extends SpecifierTypeAbstract implements SpecifierTypesInterface
{
    /**
     * @var array $types Доступные типы значений.
     */

    protected array $types = [
        'string',
        'array'
    ];

    /**
     * Метод обработки значения в зависимости от спецификатора.
     * 
     * @return self Возвращает свой контекст.
     */

    public function process(): self
    {
        if (is_array($this->value)) {
            foreach ($this->value as &$value) {
                if (!is_array($value)) {
                    $value = Helper::stringApostrophe($value);
                } else {
                    throw new Exception('Внутри значения тип array не допустим');
                }
            }

            $this->value = implode(", ", $this->value);
        } else {
            $this->value = Helper::stringApostrophe($this->value);
        }

        return $this;
    }

}