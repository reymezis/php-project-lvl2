<?php

namespace diff\gendiff;

use Exception;

use function diff\astBuilder\buildAST;
use function diff\formatters\stylish\formatter;
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

function run()
{
    $params = [
        'help'         => true,
        'version'      => "Ver. 0.6",
        'optionsFirst' => true,
    ];
    $args = \Docopt::handle(DOC, $params);

    echo genDiff($args->args['<firstFile>'], $args->args['<secondFile>'], $args['--format']);
}

function getReadableValue($value)
{
    if (gettype($value) == 'NULL') {
            return "null";
    }
    return trim(var_export($value, true), "'");
}

function genDiff($pathToFile1, $pathToFile2, $format = "stylish")
{
    $data1 = readFile($pathToFile1);
    $data2 = readFile($pathToFile2);
    $ast = buildAST($data1, $data2);
    switch ($format) {
        case 'stylish':
            return formatter($ast);
        default:
            throw new Exception("Unknown output format {$format}!");
    }
}
