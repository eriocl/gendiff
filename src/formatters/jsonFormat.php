<?php

namespace Gendiff\Src\Formatters\Json;

function makeJson($diffTree)
{
    return json_encode($diffTree, JSON_PRETTY_PRINT) . "\n";
}
