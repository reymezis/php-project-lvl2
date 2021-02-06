<?php

namespace diff\formatters\plain;

use function Differ\Differ\getReadableValue;

function stringify($value): ?string
{
    $iter = function ($currentValue): string {
        if (
            gettype($currentValue) === "NULL"
            || gettype($currentValue) === "boolean"
            || gettype($currentValue) === "integer"
        ) {
            return getReadableValue($currentValue);
        }
        if (is_object($currentValue) || is_array($currentValue)) {
            return "[complex value]";
        }

        $value = getReadableValue($currentValue);
        return "'$value'";
    };

    return $iter($value);
}

function formatter($ast): string
{
    $iter = function ($ast, $accKeys) use (&$iter): string {
        $result = array_map(function ($currentValue) use (&$iter, $accKeys): ?string {
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

        $filteredData = array_filter($result, function ($item): bool {
            return $item !== null;
        });
        return implode("\n", $filteredData);
    };
    return $iter($ast, "");
}
