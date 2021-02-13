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

function format($diff): string
{
    $iter = function ($diff, $ancestry) use (&$iter): string {
        $result = array_map(function ($node) use (&$iter, $ancestry): ?string {
            $fullPathToNode = strlen($ancestry) === 0 ? $node['key'] : "{$ancestry}.{$node['key']}";
            switch ($node["type"]) {
                case 'nested':
                    return $iter($node['children'], $fullPathToNode);
                case 'added':
                    return "Property '{$fullPathToNode}'"
                        . " was added with value: "
                        . stringify($node["value"]);
                case 'removed':
                    return "Property '{$fullPathToNode}' was removed";
                case 'unchanged':
                    return null;
                case 'changed':
                    return "Property '{$fullPathToNode}' was updated. From "
                        . stringify($node["value1"])
                        . " to "
                        . stringify($node["value2"]);
                default:
                    throw new \Exception("Unknown state {$node["type"]}!");
            }
        }, $diff);

        $filteredData = array_filter($result, function ($item): bool {
            return $item !== null;
        });
        return implode("\n", $filteredData);
    };
    return $iter($diff, "");
}
