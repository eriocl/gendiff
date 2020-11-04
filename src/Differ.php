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

function gendiff($path1, $path2, $format = 'pretty')
{
    $fileBeforeFormat = pathinfo($path1, PATHINFO_EXTENSION);
    $fileAfterFormat = pathinfo($path2, PATHINFO_EXTENSION);
    $contentBefore = file_get_contents($path1);
    $contentAfter = file_get_contents($path2);
    $dataBefore = parse($contentBefore, $fileBeforeFormat);
    $dataAfter = parse($contentAfter, $fileAfterFormat);
    $diffTree = getDiffTree($dataBefore, $dataAfter);


}

public function getDiffTree($dataBefore, $dataAfter)
{
    $keysData1 = array_keys($dataBefore);
    $keysData2 = array_keys($dataAfter);
    $keys = array_unique(array_merge($keysData1, $keysData2));
    sort($keys);
}
