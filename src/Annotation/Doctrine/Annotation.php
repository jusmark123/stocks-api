<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\Annotation\Doctrine;

use Doctrine\Common\Annotations\AnnotationReader;
use ReflectionClass;

class Annotation
{
    public static $reader;

    public static function getAnnotationsForClass($className)
    {
        $class = new ReflectionClass($className);

        return Annotation::$reader->getClassAnnotation($class);
    }
}

Annotation::$reader = new AnnotationReader();
