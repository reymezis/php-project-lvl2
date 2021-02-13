<?php

namespace Differ\Formatters;

use Exception;

use function Differ\Formatters\Stylish\format as formatToStylish;
use function Differ\Formatters\Plain\format as formatToPlain;
use function Differ\Formatters\Json\format as formatToJson;

function render($diff, $format): string
{
    switch ($format) {
        case 'stylish':
            return formatToStylish($diff);
        case 'plain':
            return formatToPlain($diff);
        case 'json':
            return formatToJson($diff);
        default:
            throw new Exception("Unknown output format {$format}!");
    }
}
