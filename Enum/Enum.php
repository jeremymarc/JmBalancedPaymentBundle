<?php

namespace Jm\BalancedPaymentBundle\Enum;

class Enum
{
    public static function toArray()
    {
        $class = new \ReflectionClass(get_called_class());

        return $class->getConstants();
    }

    public static function values()
    {
        $class = new \ReflectionClass(get_called_class());

        return array_keys($class->getConstants());
    }
}
