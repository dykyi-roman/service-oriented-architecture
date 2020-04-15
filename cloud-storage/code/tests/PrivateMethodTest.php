<?php

final class PrivateMethodTest
{
    public static function access(string $class, string $name): ReflectionMethod
    {
        $class = new ReflectionClass($class);
        $method = $class->getMethod($name);
        $method->setAccessible(true);

        return $method;
    }
}
