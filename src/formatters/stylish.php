<?php

namespace diff\formatters\stylish;

use function Differ\Differ\getReadableValue;
use function Funct\Collection\flattenAll;

const REPLACER = ' ';
const SPACES_COUNT = 4;
const CORRECTIVE_INDENT = 2;

function stringify($value, $currentDepth): string
{
    $iter = function ($currentValue, $depth) use (&$iter): ?string {
        if (!is_object($currentValue)) {
            return getReadableValue($currentValue);
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


function formatter($ast): string
{
    $iter = function ($ast, $depth) use (&$iter): string {
        $indentSize = ($depth * SPACES_COUNT) - CORRECTIVE_INDENT;
        $currentIndent = str_repeat(REPLACER, $indentSize);
        $bracketIndent = str_repeat(REPLACER, $depth * SPACES_COUNT - SPACES_COUNT);
        $result = array_map(function ($currentValue) use (&$iter, $currentIndent, $depth): string {
            switch ($currentValue["type"]) {
                case 'added':
                    return "{$currentIndent}+ {$currentValue['key']}: " . stringify($currentValue["value"], $depth + 1);
                case 'removed':
                    return "{$currentIndent}- {$currentValue['key']}: " . stringify($currentValue["value"], $depth + 1);
                case 'unchanged':
                    return "{$currentIndent}  {$currentValue['key']}: " . stringify($currentValue["value"], $depth + 1);
                case 'changed':
                    return "{$currentIndent}- {$currentValue['key']}: "
                        . stringify($currentValue["value"][0], $depth + 1)
                        . "\n" . "{$currentIndent}+ {$currentValue['key']}: "
                        . stringify($currentValue["value"][1], $depth + 1);
                case 'nested':
                    return "{$currentIndent}  {$currentValue['key']}: " . $iter($currentValue['children'], $depth + 1);
                default:
                    throw new \Exception("Unknown state {$currentValue["type"]}!");
            }
        }, $ast);
        $flattenedResult = implode("\n", flattenAll($result));
        return '{' . "\n" . $flattenedResult .  "\n" . "{$bracketIndent}}";
    };
    return $iter($ast, 1);
}
