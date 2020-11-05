<?php

namespace Gendiff\src\formatters;

function makeJson($diffTree)
{
    return json_encode($diffTree, JSON_PRETTY_PRINT) . "\n";
}
