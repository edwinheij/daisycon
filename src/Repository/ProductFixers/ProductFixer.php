<?php

namespace Bahjaat\Daisycon\Repository\ProductFixers;

class ProductFixer
{
    public static function fix($data)
    {
        collect($data)->keys()->map(function($column) use (&$data) {
            $class = __NAMESPACE__ . '\\' . ucfirst(camel_case($column)) . 'Fixer';
            if (class_exists($class) && method_exists($class, 'fix')) {
                $data = (new $class)->fix($data);
            }
        });
        return $data;
    }
}