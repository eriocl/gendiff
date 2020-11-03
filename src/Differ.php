<?php

namespace Gendiff\Src\Differ;

use function Gendiff\Src\Parsers\parse;

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

function gendiff($path1, $path2)
{
    $fileBeforeFormat = pathinfo($path1, PATHINFO_EXTENSION);
    $fileAfterFormat = pathinfo($path2, PATHINFO_EXTENSION);
    $contentBefore = file_get_contents($path1);
    $contentAfter = file_get_contents($path2);
    $dataBefore = parse($contentBefore, $fileBeforeFormat);
    $dataAfter = parse($contentAfter, $fileAfterFormat);
    $dataBefore = convertValuesToString($dataBefore);
    $dataAfter = convertValuesToString($dataAfter);
    $keysData1 = array_keys($dataBefore);
    $keysData2 = array_keys($dataAfter);
    $keys = array_unique(array_merge($keysData1, $keysData2));
    sort($keys);
    return array_reduce($keys, function ($acc, $key) use ($dataBefore, $dataAfter) {
        if (array_key_exists($key, $dataBefore) && !array_key_exists($key, $dataAfter)) {
            $acc .= "- {$key}: {$dataBefore[$key]}\n";
        }
        if (!array_key_exists($key, $dataBefore) && array_key_exists($key, $dataAfter)) {
            $acc .= "+ {$key}: {$dataAfter[$key]}\n";
        }
        if (array_key_exists($key, $dataBefore) && array_key_exists($key, $dataAfter)) {
            if ($dataBefore[$key] === $dataAfter[$key]) {
                $acc .= "  {$key}: {$dataAfter[$key]}\n";
            } else {
                $acc .= "- {$key}: {$dataBefore[$key]}\n+ {$key}: {$dataAfter[$key]}\n";
            }
        }
        return $acc;
    }, '');
}
