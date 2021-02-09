<?php

namespace Differ\Formatters\Json;

function formatter(array $ast): string
{
    return json_encode($ast, JSON_THROW_ON_ERROR);
}
