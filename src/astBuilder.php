<?php

namespace diff\astBuilder;

use function Funct\Collection\sortBy;

function buildAST(object $data1, object $data2): array
{
    $keys = array_keys(array_merge(get_object_vars($data1), get_object_vars($data2)));
    $sortedKeys = array_values(sortBy($keys, function ($key) {
        return $key;
    }));
    $ast = array_map(function ($key) use ($data1, $data2): array {
        if (property_exists($data1, $key) && property_exists($data2, $key)) {
            if (is_object($data1->$key) && is_object($data2->$key)) {
                return [
                    "key" => $key,
                    "type" => 'nested',
                    "value" => null,
                    "children" => buildAST($data1->$key, $data2->$key),
                ];
            } else {
                if ($data1->$key === $data2->$key) {
                    return [
                        "key" => $key,
                        "type" => 'unchanged',
                        "value" => $data1->$key
                    ];
                } elseif ($data1->$key !== $data2->$key) {
                    return [
                        "key" => $key,
                        "type" => 'changed',
                        "value" => [$data1->$key, $data2->$key],
                    ];
                }
            }
        } elseif (!property_exists($data2, $key)) {
            return [
                "key" => $key,
                "type" => 'removed',
                "value" => $data1->$key,
            ];
        } elseif (!property_exists($data1, $key)) {
            return [
                "key" => $key,
                "type" => 'added',
                "value" => $data2->$key
            ];
        }
    }, $sortedKeys);
    return $ast;
}
