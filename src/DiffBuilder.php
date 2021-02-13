<?php

namespace Differ\DiffBuilder;

use function Funct\Collection\sortBy;
use function Funct\Collection\union;

function buildDiff(object $data1, object $data2): array
{
    $keys = union(array_keys(get_object_vars($data1)), array_keys(get_object_vars($data2)));
    $sortedKeys = array_values(sortBy($keys, function ($key) {
        return $key;
    }));
    $diff = array_map(function ($key) use ($data1, $data2): array {
        if (!property_exists($data2, $key)) {
            return [
                "key" => $key,
                "type" => 'removed',
                "value" => $data1->$key,
            ];
        }
        if (!property_exists($data1, $key)) {
            return [
                "key" => $key,
                "type" => 'added',
                "value" => $data2->$key
            ];
        }
        if (is_object($data1->$key) && is_object($data2->$key)) {
            return [
                "key" => $key,
                "type" => 'nested',
                "children" => buildDiff($data1->$key, $data2->$key),
            ];
        }
        if ($data1->$key !== $data2->$key) {
            return [
                "key" => $key,
                "type" => 'changed',
                "value1" => $data1->$key,
                "value2" => $data2->$key,
            ];
        }
        return [
            "key" => $key,
            "type" => 'unchanged',
            "value" => $data1->$key
        ];
    }, $sortedKeys);
    return $diff;
}
