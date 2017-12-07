<?php
/**
 * Created by PhpStorm.
 * User: asandoval
 * Date: 06/12/2017
 * Time: 10:12
 */

namespace Lithium\Helpers;


final class RegexpHelper {

    private static function getDocObjectProperties(string $docObjectBody): ?array {
        if (\is_null($docObjectBody) || !\is_string($docObjectBody) || \strlen($docObjectBody) <= 0)
            return null;
        if (!\preg_match_all('/(' . Values::METADATA_TOKEN_PREFIX .'[' . Values::METADATA_TOKEN_SCHEMA . ']+)(?:\s+)?([^\n]+)?/i',
            $docObjectBody,
            $properties,
            \PREG_SET_ORDER,
            0))
            return null;
        if (\is_null($properties) || !\is_array($properties) || \count($properties) <= 0)
            return null;
        $propertiesArr = [];
        foreach ($properties as $prop) {
            if (!\is_null($prop) && \is_array($prop) && \count($prop) > 1 && \count($prop) < 4) {
                $tmpKey = \trim((string)$prop[1]);
                $tmpValue = \trim((string)($prop[2] ?? ""));
                if (!isset($propertiesArr[$tmpKey]))
                    $propertiesArr[$tmpKey] = [];
                $propertiesArr[$tmpKey][] = $tmpValue;
            }
        }
        return \count($propertiesArr) > 0 ? $propertiesArr : null;
    }

    private static function getDocObjectBody(string $docObject): ?string {
        if (!\preg_match("/^" . Values::METADATA_TOKEN_PREFIX ."[" . Values::METADATA_TOKEN_SCHEMA . "]+\s*\(.*\)$/is", $docObject))
            return null; // i'm not an object
        if (!\preg_match_all("/^\s*(?:" . Values::METADATA_TOKEN_PREFIX ."[" . Values::METADATA_TOKEN_SCHEMA . "]+)\s*\((.*)\)\s*$/is",
            $docObject,
            $docObjectBody,
            \PREG_SET_ORDER,
            0))
            return null; //fail at get body
        if (\is_null($docObjectBody) || !\is_array($docObjectBody) || \count($docObjectBody) != 1)
            return null;
        $docObjectBody = $docObjectBody[0];
        if (\is_null($docObjectBody) || !\is_array($docObjectBody) || \count($docObjectBody) != 2)
            return null;
        $docObjectBody = $docObjectBody[1];
        if (\is_null($docObjectBody) || !\is_string($docObjectBody))
            return null;
        return $docObjectBody;
    }

    private static function getDocObjects(string &$doc, bool $cleanUp = false): ?array {
        $pattern =
            "/(" . Values::METADATA_TOKEN_PREFIX . "[" . Values::METADATA_TOKEN_SCHEMA . "]+)\((?:[^()]|(?R))*\)/i";
        if (!\preg_match_all($pattern, $doc, $objects, \PREG_SET_ORDER, 0))
            return null;
        if (\is_null($objects) || !\is_array($objects) || \count($objects) <= 0)
            return null;
        if ($cleanUp)
            $doc = \preg_replace($pattern, "", $doc);
        $validObjects = [];
        foreach ($objects as $object) {
            if (!\is_null($object) && \is_array($object) || \count($object) == 2) {
                $validObjects[\trim((string)$object[1])] = \trim((string)$object[0]);
            }
        }
        return \count($validObjects) <= 0 ? null : $validObjects;
    }

    private static function docToArray(string $doc, string $metaNodeName = null): ?array {
        if (!\is_null($metaNodeName)) {
            $mainObjects = self::getDocObjects($doc);
            if (\is_null($mainObjects))
                return null;
            foreach ($mainObjects as $key => $obj) {
                if (\preg_match("/{$metaNodeName}/i", $key))
                    return self::docToArray($obj);
            }
            return null;
        }
        $doc = self::getDocObjectBody($doc);
        if (\is_null($doc) || \strlen(\trim($doc)) <= 0)
            return [];
        $finalArray = [];
        $subObjects = self::getDocObjects($doc, true);
        if (!\is_null($subObjects)) {
            foreach ($subObjects as $key => $obj) {
                if (!isset($finalArray[$key]))
                    $finalArray[$key] = [];
                $finalArray[$key][] = self::docToArray($obj);
            }
        }
        $subProperties = self::getDocObjectProperties($doc);
        if (!\is_null($subProperties)) {
            foreach ($subProperties as $key => $prop) {
                $finalArray[$key] = $prop;
            }
        }
        return $finalArray;
    }

    private static function getCleanDocText(string $doc) {
        $doc = \preg_replace("/((?:^\s*\*+\/*)|(?:^\s*\/\*+))/m", "", $doc);
        $doc = \preg_replace("/\s*\=\s*/", " ", $doc);
        return \preg_replace("/\,\s*" . Values::METADATA_TOKEN_PREFIX ."/m", "\n" . Values::METADATA_TOKEN_PREFIX, $doc);
    }

    public static function getDocArray(string $doc, string $mainNodeName): ?array {
        $mainNodeName = Values::METADATA_TOKEN_PREFIX . $mainNodeName;
        if (!\preg_match("/{$mainNodeName}/", $doc))
            return null;
        $cleanDoc = self::getCleanDocText($doc);
        if (\is_null($cleanDoc) || \strlen($cleanDoc) <= 0)
            return null;
        return self::docToArray($cleanDoc, $mainNodeName);
    }

    public static function isSetterMethod(string $methodName): bool {
        return \preg_match("/^set.+$/", $methodName);
    }

    public static function getCleanSetterName(string $propertyName): string {
        $propertyName = \preg_replace("/". Values::METADATA_TOKEN_PREFIX ."/", "", $propertyName);
        $propertyName = \ucfirst($propertyName);
        return "set$propertyName";
    }
}