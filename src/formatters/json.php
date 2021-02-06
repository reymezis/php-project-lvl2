<?php

namespace diff\formatters\json;

function formatter($ast): string
{
    return json_encode($ast, JSON_PRETTY_PRINT);
}
