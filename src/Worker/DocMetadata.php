<?php
/**
 * Created by PhpStorm.
 * User: asandoval
 * Date: 06/12/2017
 * Time: 10:17
 */

namespace Lithium\Worker;


use Lithium\Exceptions\ClassNotFoundException;
use Lithium\Exceptions\MissingParameterException;
use Lithium\Exceptions\NullPointerException;
use Lithium\Helpers\ReflectionHelper;
use Lithium\Helpers\RegexpHelper;

final class DocMetadata implements Worker {

    /**
     * @param string $doc
     * @param string $metaClassName
     * @return null
     * @throws ClassNotFoundException
     * @throws MissingParameterException
     * @throws NullPointerException
     */
    public static function get(string $doc, string $metaClassName) {
        if (\is_null($doc) || !\is_string($doc) || \strlen(\trim($doc)) <= 0)
            return null;
        if (!\class_exists($metaClassName))
            return null;

        $reflectionClass = new \ReflectionClass($metaClassName);
        if (\is_null($reflectionClass))
            return null;
        $docArray = self::getDocArray($doc, $reflectionClass->getShortName());
        if (\is_null($docArray))
            return null;
        unset($reflectionClass);
        return ReflectionHelper::setMetadataObjectProperties($docArray, $metaClassName);
    }

    /**
     * @param string $doc
     * @param string $mainNodeName
     * @return array|null
     */
    private static function getDocArray(string $doc, string $mainNodeName): ?array {
        $docArray = RegexpHelper::getDocArray($doc, $mainNodeName);
        if (\is_null($docArray) || !\is_array($docArray))
            return null;
        return $docArray;
    }

}