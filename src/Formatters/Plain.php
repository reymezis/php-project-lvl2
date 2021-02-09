<?php

namespace Differ\Formatters\Plain;

function stringify($value): string
{
    if (is_null($value)) {
        return "null";
    }
    if (is_bool($value)) {
        return $value ? "true" : "false";
    }
    if (is_object($value) || is_array($value)) {
        return "[complex value]";
    }
    if (is_string($value)) {
        return "'$value'";
    }
    return "{$value}";
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
                        . stringify($currentValue["value1"])
                        . " to "
                        . stringify($currentValue["value2"]);
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
