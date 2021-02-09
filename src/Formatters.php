<?php

namespace Differ\Formatters;

use Exception;

use function Differ\Formatters\Stylish\formatter as stylish;
use function Differ\Formatters\Plain\formatter as plain;
use function Differ\Formatters\Json\formatter as json;

function render($ast, $format): string
{
    switch ($format) {
        case 'stylish':
            return stylish($ast);
        case 'plain':
            return plain($ast);
        case 'json':
            return json($ast);
        default:
            throw new Exception("Unknown output format {$format}!");
    }
}

function getReadableValue($value): string
{
    if (is_null($value)) {
        return "null";
    }
    return trim(var_export($value, true), "'");
}
