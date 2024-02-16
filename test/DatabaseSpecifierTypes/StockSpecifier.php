<?php
namespace FpDbTest\DatabaseSpecifierTypes;

use FpDbTest\DatabaseInterfaces\DatabaseSpecifierTypesInterface;
use FpDbTest\DatabaseSpecifier\DatabaseSpecifier;
use Exception;

/**
 * SharpSpecifier - Класс обработки спецификатора ? (место вставки).
 */

class StockSpecifier extends DatabaseSpecifier implements DatabaseSpecifierTypesInterface
{

    /**
     * @var array $types Доступные типы значений.
     */

    private array $types = [
        'string',
        'integer',
        'float',
        'double',
        'boolean',
        'NULL'
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
        $this->value = $this->defaultConvert($this->value);

        if (gettype($this->value) == "string") {
            $this->value = $this->stringScreening($this->value);
        }

        return $this->value;
    }
}