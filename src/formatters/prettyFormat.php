<?php

namespace Gendiff\src\formatters;

function makePretty($difftree)
{

}

function convertValuesToString($values)
{
    $result = [];
    foreach ($values as $key => $value) {
        if (is_bool($value)) {
            $result[$key] = $value ? 'true' : 'false';
        } else {
            $result[$key] = $value;
        }
    }
    return $result;
}