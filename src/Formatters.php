<?php

namespace diff\Formatters;

use Exception;

use function diff\formatters\stylish\formatter as stylish;
use function diff\formatters\plain\formatter as plain;
use function diff\formatters\json\formatter as json;

function render($ast, $format)
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
