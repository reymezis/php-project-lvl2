<?php

namespace diff\formatters\json;

function formatter(array $ast): string
{
    return json_encode($ast, JSON_THROW_ON_ERROR);
}
