<?php

namespace Differ\Formatters\Stylish;

use function Funct\Collection\flattenAll;

const REPLACER = ' ';
const SPACES_COUNT = 4;
const CORRECTIVE_INDENT = 2;

function stringify($value, $currentDepth): string
{
    $iter = function ($currentValue, $depth) use (&$iter): string {
        if (is_null($currentValue)) {
            return "null";
        }
        if (is_bool($currentValue)) {
            return $currentValue ? "true" : "false";
        }
        if (is_string($currentValue)) {
            return $currentValue;
        }
        if (is_int($currentValue)) {
            return $currentValue;
        }
        $indentSize = $depth * SPACES_COUNT;
        $currentIndent = str_repeat(REPLACER, $indentSize);
        $bracketIndent = str_repeat(REPLACER, $depth * SPACES_COUNT - SPACES_COUNT);
        $objectValues = get_object_vars($currentValue);
        $objectKeys = array_keys($objectValues);
        $lines = array_map(function ($key, $val) use ($currentIndent, $iter, $depth): string {
            return "{$currentIndent}{$key}: {$iter($val, $depth + 1)}";
        }, $objectKeys, $objectValues);

        $result = ['{', implode("\n", $lines), "{$bracketIndent}}"];
        return implode("\n", $result);
    };

    return $iter($value, $currentDepth);
}


function format($diff): string
{
    $iter = function ($diff, $depth) use (&$iter): string {
        $indentSize = ($depth * SPACES_COUNT) - CORRECTIVE_INDENT;
        $currentIndent = str_repeat(REPLACER, $indentSize);
        $bracketIndent = str_repeat(REPLACER, $depth * SPACES_COUNT - SPACES_COUNT);
        $result = array_map(function ($node) use (&$iter, $currentIndent, $depth): string {
            switch ($node["type"]) {
                case 'added':
                    return "{$currentIndent}+ {$node['key']}: " . stringify($node["value"], $depth + 1);
                case 'removed':
                    return "{$currentIndent}- {$node['key']}: " . stringify($node["value"], $depth + 1);
                case 'unchanged':
                    return "{$currentIndent}  {$node['key']}: " . stringify($node["value"], $depth + 1);
                case 'changed':
                    return "{$currentIndent}- {$node['key']}: "
                        . stringify($node["value1"], $depth + 1)
                        . "\n" . "{$currentIndent}+ {$node['key']}: "
                        . stringify($node["value2"], $depth + 1);
                case 'nested':
                    return "{$currentIndent}  {$node['key']}: " . $iter($node['children'], $depth + 1);
                default:
                    throw new \Exception("Unknown state {$node["type"]}!");
            }
        }, $diff);
        $flattenedResult = implode("\n", flattenAll($result));
        return '{' . "\n" . $flattenedResult .  "\n" . "{$bracketIndent}}";
    };
    return $iter($diff, 1);
}
