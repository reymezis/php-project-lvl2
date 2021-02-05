<?php

namespace diff\astBuilder;

function buildAST($data1, $data2)
{
    $keys = array_keys(array_merge(get_object_vars($data1), get_object_vars($data2)));
    sort($keys);
    return array_map(function ($key) use ($data1, $data2) {
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
    }, $keys);
}