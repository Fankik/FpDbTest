<?php
namespace FpDbTest\DatabaseSpecifierTypes;

use FpDbTest\DatabaseInterfaces\DatabaseSpecifierTypesInterface;
use FpDbTest\DatabaseSpecifier\DatabaseSpecifier;
use Exception;

/**
 * SharpSpecifier - Класс обработки спецификатора ?#.
 * Массив значений или значение. Значение преобразуется в список. 
 */

class SharpSpecifier extends DatabaseSpecifier implements DatabaseSpecifierTypesInterface
{
    /**
     * @var array $types Доступные типы значений.
     */

    private array $types = [
        'string',
        'array'
    ];

    /**
     * @var mixed $value Обрабатываемое значение.
     */

    private mixed $value;

    /**
     * @param mixed $value Значение для обработки.
     */

    public function __construct(mixed $value)
    {
        $type = gettype($value);

        if (in_array($type, $this->types)) {
            $this->value = $value;
        } else {
            throw new Exception("Тип $type не разрешен");
        }

    }

    /**
     * Метод возвращает обработанное значение в зависимости от спецификатора.
     * 
     * @return mixed Обработанное значение.
     */

    public function get(): mixed
    {

        if (is_array($this->value)) {
            foreach ($this->value as &$value) {
                if (!is_array($value)) {
                    $value = $this->defaultConvert($value);
                    $value = $this->stringApostrophe($value);

                } else {
                    throw new Exception('Внутри значения тип array не допустим');
                }
            }

            $this->value = implode(", ", $this->value);
        } else {
            $this->value = $this->defaultConvert($this->value);
            $this->value = $this->stringApostrophe($this->value);
        }

        return $this->value;
    }
}