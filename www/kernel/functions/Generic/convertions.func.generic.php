<?php
if (!defined('DIR_CLASS_SYS')) {
    die('Accès direct interdit');
}
function objectToArray($object)
{
    if (is_object($object)) {
        $reflectionClass = new ReflectionClass($object);
        $properties = $reflectionClass->getProperties();
        $array = [];

        foreach ($properties as $property) {
            $property->setAccessible(true);
            $name = $property->getName();
            $value = $property->getValue($object);
            $array[$name] = objectToArray($value);
        }

        return $array;
    }

    if (is_array($object)) {
        return array_map('objectToArray', $object);
    }

    return $object;
}

