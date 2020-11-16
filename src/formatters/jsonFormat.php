<?php

namespace Gendiff\Formatters\jsonFormat;

function makeJson($diffTree)
{
    return json_encode($diffTree, JSON_PRETTY_PRINT);
}
