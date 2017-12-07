<?php
/**
 * Created by PhpStorm.
 * User: asandoval
 * Date: 06/12/2017
 * Time: 10:13
 */

namespace Lithium\Helpers;


use Lithium\Exceptions\ClassNotFoundException;
use Lithium\Exceptions\MissingParameterException;
use Lithium\Exceptions\NullPointerException;

final class ReflectionHelper {

    /**
     * @param string                $className
     * @param \ReflectionClass|null $reflectionClass
     * @return object
     * @throws ClassNotFoundException
     * @throws MissingParameterException
     * @throws NullPointerException
     */
    public static function newClassNameInstance(string $className, \ReflectionClass &$reflectionClass = null) {

        if (!\class_exists($className))
            throw new ClassNotFoundException("Class $className is not defined");
        $reflectionClass = new \ReflectionClass($className);
        if (\is_null($reflectionClass))
            throw new NullPointerException("Could not create reference to class $className");
        $reflectionConstructor = $reflectionClass->getConstructor();
        if (!\is_null($reflectionConstructor) && $reflectionConstructor->getNumberOfRequiredParameters() > 0)
            throw new MissingParameterException("To many parameters in $className constructor");
        unset($reflectionConstructor);
        return $reflectionClass->newInstance();
    }

    /**
     * @param array  $objectArray
     * @param string $className
     * @return null
     * @throws ClassNotFoundException
     * @throws MissingParameterException
     * @throws NullPointerException
     */
    public static function setMetadataObjectProperties(array $objectArray, string $className) {
        /** @var \ReflectionClass $reflectionClass */
        if (\is_null($objectArray) || !\is_array($objectArray))
            return null;
        $object = self::newClassNameInstance($className, $reflectionClass);
        if (\count($objectArray) <= 0)
            return $object;
        foreach ($objectArray as $propertyName => $propertyValue) {
            $setterName = RegexpHelper::getCleanSetterName($propertyName);
            if (!$reflectionClass->hasMethod($setterName))
                continue;
            $reflectionSetter = $reflectionClass->getMethod($setterName);
            if ($reflectionSetter->getNumberOfParameters() != 1)
                throw new MissingParameterException("Too many parameters for method $setterName in class $className");
            $reflectionParameter = $reflectionSetter->getParameters()[0];
            if ($reflectionParameter->hasType()) {
                $reflectionParameterClass = $reflectionParameter->getClass();
                if (\is_null($reflectionParameterClass)) {
                    $propertyValue = ($reflectionParameter->isVariadic()) ? $propertyValue : [$propertyValue[0]];
                    $reflectionSetter->invokeArgs($object, $propertyValue);
                } else {
                    $setterObjectParameter = [];
                    foreach ($propertyValue as $subPropertyArray) {
                        $setterObjectParameter[] =
                            self::setMetadataObjectProperties($subPropertyArray, $reflectionParameterClass->getName());
                        if (!$reflectionParameter->isVariadic())
                            break;
                    }
                    $reflectionSetter->invokeArgs($object, $setterObjectParameter);
                }
            } else {
                $propertyValue = ($reflectionParameter->isVariadic()) ? $propertyValue : [$propertyValue[0]];
                $reflectionSetter->invokeArgs($object, $propertyValue);
            }
        }
        return $object;
    }


}