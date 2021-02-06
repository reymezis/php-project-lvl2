<?php

namespace Differ\Differ;

use function diff\astBuilder\buildAST;
use function diff\Formatters\render;
use function diff\parsers\parseData;
use function diff\parsers\readFile;

const DOC = <<<DOC
Generate diff

Usage:
  gendiff (-h|--help)
  gendiff (-v|--version)
  gendiff [--format <fmt>] <firstFile> <secondFile>

Options:
  -h --help                     Show this screen
  -v --version                  Show version
  --format <fmt>                Report format [default: stylish]
DOC;

function run(): void
{
    $params = [
        'help'         => true,
        'version'      => "Ver. 0.6",
        'optionsFirst' => true,
    ];
    $args = \Docopt::handle(DOC, $params);

    echo genDiff($args->args['<firstFile>'], $args->args['<secondFile>'], $args['--format']);
}

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
    $ast = buildAST($parsedData1, $parsedData2);
    return render($ast, $format);
}
