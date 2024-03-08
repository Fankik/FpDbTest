<?php
namespace FpDbTest\DatabaseSpecifierTypes;

use FpDbTest\DatabaseHelper\DatabaseHelper;
use FpDbTest\DatabaseInterfaces\DatabaseSpecifierTypesInterface;
use FpDbTest\DatabaseSpecifier\DatabaseSpecifier;
use Exception;

/**
 * ASpecifier - Класс обработки спецификатора ?a.
 * Массив значений преобразуется либо в список значений через запятую (список), 
 * либо в пары идентификатор и значение через запятую (ассоциативный массив).
 */

class ASpecifier extends DatabaseSpecifier implements DatabaseSpecifierTypesInterface
{
    /**
     * @var array $types Доступные типы значений.
     */

    private array $types = [
        'array',
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
        $values = [];

        if ($this->is_associate($this->value)) {
            foreach ($this->value as $key => $value) {
                if (!is_array($value)) {
                    if (gettype($value) == 'string') {
                        $value = DatabaseHelper::stringScreening($value);
                    }

                    $value = DatabaseHelper::defaultConvert($value);

                    $values[] = DatabaseHelper::stringApostrophe($key) . ' = ' . $value;
                } else {
                    throw new Exception('Внутри значения тип array не допустим');
                }
            }
        } else {
            foreach ($this->value as $value) {
                if (!is_array($value)) {

                    if (gettype($value) == 'string') {
                        $value = DatabaseHelper::stringScreening($value);
                    }

                    $values[] = DatabaseHelper::defaultConvert($value);
                } else {
                    throw new Exception('Внутри значения тип array не допустим');
                }
            }
        }

        $this->value = implode(', ', $values);

        return $this->value;
    }
    
    /**
     * Метод для проверки ассоциативный массив или нет.
     * 
     * @param array $array Массив
     * @return bool Возвращает `true` или `false`.
     */

    private function is_associate(array $array): bool
    {
        if ($array === []) {
            return false;
        }

        return !(array_keys($array) === range(0, count($array) - 1));
    }
}