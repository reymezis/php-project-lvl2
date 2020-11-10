<?php

namespace diff\gendiff;

const DOC = <<<DOC
Generate diff

Usage:
  gendiff (-h|--help)
  gendiff (-v|--version)
  gendiff [--format <fmt>] <firstFile> <secondFile>

Options:
  -h --help                     Show this screen
  -v --version                  Show version
  --format <fmt>                Report format [default: pretty]
DOC;

function run()
{
    $params = [
        'help'         => true,
        'version'      => "Ver. 0.2",
        'optionsFirst' => true,
    ];
    $args = \Docopt::handle(DOC, $params);

    genDiff($args->args['<firstFile>'], $args->args['<secondFile>']);
}

function readFile($path)
{
    $absolutePath = realpath($path);
    return $data = file_get_contents($absolutePath);
}

function getReadableValue($value)
{
    return trim(var_export($value, true), "'");
}

function genDiff($pathToFile1, $pathToFile2)
{
    $data1 = (array) json_decode(readFile($pathToFile1));
    $data2 = (array) json_decode(readFile($pathToFile2));
    $keys = array_unique(array_merge(array_keys($data1), array_keys($data2)));

    $result = array_reduce($keys, function ($acc, $key) use ($data1, $data2) {
        if (!array_key_exists($key, $data1)) {
            $acc['added' . "-" . $key] = $key . ": " . getReadableValue($data2[$key]);
        } elseif (!array_key_exists($key, $data2)) {
            $acc['deleted' . "-" . $key] = $key . ": " . getReadableValue($data1[$key]);
        } elseif ($data1[$key] !== $data2[$key]) {
            $acc['original' . "-" . $key] = $key . ": " . getReadableValue($data1[$key]);
            $acc['changed' . "-" . $key] = $key . ": " . getReadableValue($data2[$key]);
        } else {
            $acc['unchanged' . "-" . $key] =  $key . ": " . getReadableValue($data1[$key]);
        }
        return $acc;
    }, []);

    $prettyResult = "{\n" . array_reduce(array_keys($result), function ($acc, $key) use ($result) {
        if (preg_match('/^unchanged-/', $key)) {
            $acc .= "    " . $result[$key] . "\n";
        }
        if (preg_match('/^original-/', $key)) {
            $acc .= "  " . "- " . $result[$key] . "\n";
        }
        if (preg_match('/^changed-/', $key)) {
            $acc .= "  " .  "+ " . $result[$key] . "\n";
        }
        if (preg_match('/^deleted-/', $key)) {
            $acc .= "  " .  "- " . $result[$key] . "\n";
        }
        if (preg_match('/^added-/', $key)) {
            $acc .= "  " .  "+ " . $result[$key] . "\n";
        }

        return $acc;
    }, "") . "}\n";

    echo $prettyResult;
}
