<?php

namespace Leon\Utility;

use Exception;
use ReflectionClass;
use ReflectionMethod;

/**
 * Utility
 *
 * @Author Nick Wakeman <nick@thehiredgun.tech>
 * @Since  1.0.0 (2017-03-31)
 */
class Utility
{
    /**
     * getLabelFromName
     *
     * Get a Pretty Label from a camelCaps field name like 'prettyLabel'
     *
     * @Author Nick Wakeman <nick@thehiredgun.tech>
     * @Since  1.0.0 (2017-03-31)
     *
     * @Param string $name
     */
    public static function getLabelFromName($name)
    {
        return ucwords(implode(' ', preg_split(
            '/(^[^A-Z]+|[A-Z][^A-Z]+)/',
            $name,
            -1,                         // no limit for replacement count
            PREG_SPLIT_NO_EMPTY |       // don't return empty elements
            PREG_SPLIT_DELIM_CAPTURE    // don't strip anything from output array
        )));
    }

    /**
     * instantiate
     *
     * Get an instantiated and configured object of $className, based on $configuration
     *
     * @Author Nick Wakeman <nick@thehiredgun.tech>
     * @Since  1.0.0 (2017-03-31)
     *
     * @Param string $className
     * @Param array $configuration
     */
    public static function instantiate(string $className, array $configuration = [], bool $strict = false)
    {
        $constructArguments = [];
        if (method_exists($className, '__construct')) {
            $reflectionMethod = new ReflectionMethod($className, '__construct');
            if ($reflectionMethod->isPublic() && $constructParameters = $reflectionMethod->getParameters()) {
                foreach ($constructParameters as $constructParameter) {
                    if (!$constructParameter->isOptional()) {
                        if (!isset($configuration[$constructParameter->getName()])) {
                            Throw new Exception();
                        }
                        $constructArguments[] = $configuration[$constructParameter->getName()];
                    } elseif (isset($configuration[$constructParameter->getName()])) {
                        $constructArguments[] = $configuration[$constructParameter->getName()];
                    }
                    unset($configuration[$constructParameter->getName()]);
                }
            }
        }
        $reflectionClass = new ReflectionClass($className);
        $object = $reflectionClass->newInstanceArgs($constructArguments);
        if (count($configuration)) {
            foreach ($configuration as $name => $value) {
                $methodName = 'set' . ucwords($name);
                if (!method_exists($className, $methodName)) {
                    if ($strict) {
                        Throw new Exception();
                    }
                } else {
                    $reflectionMethod = new ReflectionMethod($className, $methodName);
                    if (!$reflectionMethod->isPublic()) {
                        if ($strict) {
                            Throw new Exception();
                        }
                    } else {
                        $object->$methodName($value);
                    }
                }
            }
        }
        
        return $object;
    }
}
