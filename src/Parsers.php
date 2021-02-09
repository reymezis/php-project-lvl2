<?php

namespace Differ\Parsers;

use Symfony\Component\Yaml\Yaml;

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
