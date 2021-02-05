<?php

namespace diff\formatters\plain;

use function Differ\Differ\getReadableValue;

function stringify($value)
{
    $iter = function ($currentValue) use (&$iter) {
        if (
            getType($currentValue) === "NULL"
            || getType($currentValue) === "boolean"
            || getType($currentValue) === "integer"
        ) {
            return getReadableValue($currentValue);
        }
        if (!is_object($currentValue)) {
            $value = getReadableValue($currentValue);
            return "'$value'";
        }
        if (is_object($currentValue)) {
            return "[complex value]";
        }
    };

    return $iter($value);
}

function formatter($ast)
{
    $iter = function ($ast, $accKeys) use (&$iter) {
        $result = array_map(function ($currentValue) use (&$iter, $accKeys) {
            $currentKey = strlen($accKeys) === 0 ? $currentValue['key'] : "{$accKeys}.{$currentValue['key']}";
            switch ($currentValue["type"]) {
                case 'nested':
                    return $iter($currentValue['children'], $currentKey);
                case 'added':
                    return "Property '{$currentKey}'"
                        . " was added with value: "
                        . stringify($currentValue["value"]);
                case 'removed':
                    return "Property '{$currentKey}' was removed";
                case 'unchanged':
                    return null;
                case 'changed':
                    return "Property '{$currentKey}' was updated. From "
                        . stringify($currentValue["value"][0])
                        . " to "
                        . stringify($currentValue["value"][1]);
                default:
                    throw new \Exception("Unknown state {$currentValue["type"]}!");
            }
        }, $ast);

        $filteredData = array_filter($result, function ($item) {
            return $item !== null;
        });
        $result = implode("\n", $filteredData);
        return $result;
    };
    return $iter($ast, "");
}
