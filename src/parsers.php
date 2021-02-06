<?php

namespace diff\parsers;

use Symfony\Component\Yaml\Yaml;

function readFile(string $path): object
{
    $absolutePath = realpath($path);
    $fileFormat = pathinfo($path, PATHINFO_EXTENSION);
    $data = file_get_contents($absolutePath);
    $parsersList = [
        "json" => json_decode($data),
        "yaml"  => Yaml::parse($data, Yaml::PARSE_OBJECT_FOR_MAP),
    ];
    return $parsersList[$fileFormat];
}
