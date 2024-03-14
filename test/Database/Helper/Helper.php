<?php

namespace FpDbTest\Database\Helper;

/**
 * Helper - Для методов помощников не относящихся к основным классам обработки
 */

class Helper
{
    /**
     * Метод экранирования строки символом - `'`.
     * 
     * @param string $value Значение.
     * @return string Обработанное значение.
     */

    static public function stringScreening(string $value): string
    {
        if (substr_count($value, "'") != 2) {
            return "'" . $value . "'";
        } else {
            return $value;
        }
    }

    /**
     * Метод экранирования строки символом - `.
     * 
     * @param string $value Значение
     * @return string Обработанное значение
     */

    static public function stringApostrophe(string $value): string
    {
        if (substr_count($value, "`") != 2) {
            return "`" . $value . "`";
        } else {
            return $value;
        }
    }

    /**
     * Метод для замены значений из массива `$defaultConvertValues`.
     * Если значения нет в массив, назад вернется передаваемое значение `$value`.
     * 
     * @param mixed $value Значение.
     * @return mixed Замененное значение | Передаваемое значение.
     */

    static public function defaultConvert(mixed $value): mixed
    {
        return match ($value) {
            true => 1,
            false => 0,
            null => 'NULL',
            default => $value,
        };
    }
}