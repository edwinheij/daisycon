<?php

namespace Bahjaat\Daisycon\Repository\ProductFixers;

class ProductFixer
{
    public static function apply($model)
    {
        collect($model)->keys()->map(function($column) use ($model) {
            $class = __NAMESPACE__ . '\\' . ucfirst(camel_case($column)) . 'Fixer';
            if (class_exists($class)) {
                (new $class)->handle($model);
            }
        });
    }
}