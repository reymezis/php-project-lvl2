<?php

namespace Differ\Differ;

use function Differ\DiffBuilder\buildDiff;
use function Differ\Formatters\render;
use function Differ\Parsers\parseData;
use function Differ\Parsers\readFile;

function getReadableValue($value): string
{
    if (gettype($value) == 'NULL') {
            return "null";
    }
    return trim(var_export($value, true), "'");
}

function genDiff($pathToFile1, $pathToFile2, $format = "stylish"): string
{
    $data1 = readFile($pathToFile1);
    $data2 = readFile($pathToFile2);

    [$file1Format, $rawData1] = $data1;
    [$file2Format, $rawData2] = $data2;

    $parsedData1 = parseData($file1Format, $rawData1);
    $parsedData2 = parseData($file2Format, $rawData2);
    $ast = buildDiff($parsedData1, $parsedData2);
    return render($ast, $format);
}
