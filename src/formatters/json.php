<?php

namespace diff\formatters\json;

function formatter(array $ast): string
{
    return json_encode($ast, JSON_PRETTY_PRINT);
}
