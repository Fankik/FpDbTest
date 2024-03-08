<?php
namespace FpDbTest\DatabaseSpecifierTypes;

use FpDbTest\DatabaseHelper\DatabaseHelper;
use FpDbTest\DatabaseInterfaces\DatabaseSpecifierTypesInterface;
use FpDbTest\DatabaseSpecifier\DatabaseSpecifier;
use Exception;

/**
 * DSpecifier - Класс обработки спецификатора ?d.
 * Конвертация в целое число.
 */

class DSpecifier extends DatabaseSpecifier implements DatabaseSpecifierTypesInterface
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
        $type = gettype($this->value);

        if ($type == 'NULL') {
            return DatabaseHelper::defaultConvert($this->value);
        } else {
            return (int) $this->value;
        }

    }
}