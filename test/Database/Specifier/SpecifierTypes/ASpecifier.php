<?php
namespace FpDbTest\Database\Specifier\SpecifierTypes;

use FpDbTest\Database\Helper\Helper;
use FpDbTest\Database\Interfaces\SpecifierTypesInterface;

use Exception;

/**
 * ASpecifier - Класс обработки спецификатора ?a.
 * Массив значений преобразуется либо в список значений через запятую (список), 
 * либо в пары идентификатор и значение через запятую (ассоциативный массив).
 */

class ASpecifier extends SpecifierTypeAbstract implements SpecifierTypesInterface
{
    /**
     * @var array $types Доступные типы значений.
     */

    protected array $types = [
        'array',
    ];

    /**
     * Метод обработки значения в зависимости от спецификатора.
     * 
     * @return self Возвращает свой контекст.
     */

    public function process(): self
    {
        $values = [];
        $associate = $this->is_associate($this->value);

        foreach ($this->value as $key => $value) {
            if (!is_array($value)) {
                if (gettype($value) == 'string') {
                    $value = Helper::stringScreening($value);
                }

                $value = Helper::defaultConvert($value);

                if ($associate) {
                    $value = Helper::stringApostrophe($key) . ' = ' . $value;
                }

                $values[] = $value;
            } else {
                throw new Exception('Внутри значения тип array не допустим');
            }
        }

        $this->value = implode(', ', $values);

        return $this;
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