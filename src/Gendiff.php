<?php

namespace Differ\Differ;

use function Differ\DiffBuilder\buildDiff;
use function Differ\Formatters\render;
use function Differ\Parsers\parseData;

function genDiff($pathToFile1, $pathToFile2, $format = "stylish"): string
{
    $data1 = readFile($pathToFile1);
    $data2 = readFile($pathToFile2);

    [$file1Format, $rawData1] = $data1;
    [$file2Format, $rawData2] = $data2;

    $parsedData1 = parseData($file1Format, $rawData1);
    $parsedData2 = parseData($file2Format, $rawData2);
    $diff = buildDiff($parsedData1, $parsedData2);
    return render($diff, $format);
}

function readFile(string $path): array
{
    if (!file_exists($path)) {
        throw new \Exception("File '{$path}' unreadable or doesn't exist");
    }
    $fileFormat = pathinfo($path, PATHINFO_EXTENSION);
    $data = file_get_contents($path);
    return [$fileFormat, $data];
}
