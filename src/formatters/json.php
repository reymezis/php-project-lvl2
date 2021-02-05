<?php

namespace diff\formatters\json;

function formatter($ast)
{
    return json_encode($ast, JSON_PRETTY_PRINT);
}
