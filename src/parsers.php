<?php

namespace diff\parsers;

use Symfony\Component\Yaml\Yaml;

function readFile(string $path): array
{
    if (!file_exists($path)) {
        throw new \Exception("File '{$path}' unreadable or doesn't exist");
    }
    $absolutePath = realpath($path);
    $fileFormat = pathinfo($path, PATHINFO_EXTENSION);
    $data = file_get_contents($absolutePath);
    return [$fileFormat, $data];
}

function parseData($fileFormat, $data): object
{
    switch ($fileFormat) {
        case 'yml':
        case 'yaml':
            return Yaml::parse($data, Yaml::PARSE_OBJECT_FOR_MAP);
        case 'json':
            return json_decode($data);
        default:
            throw new \Exception("Unknown format {$fileFormat}");
    }
}
