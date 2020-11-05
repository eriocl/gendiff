<?php

namespace Gendiff\Src\Differ;

use function Gendiff\Src\Parsers\parse;
use function Gendiff\src\formatters\makeJson;
use function Gendiff\src\formatters\makePlain;
use function Gendiff\src\formatters\makePretty;

function gendiff($path1, $path2, $format = 'pretty')
{
    $fileBeforeFormat = pathinfo($path1, PATHINFO_EXTENSION);
    $fileAfterFormat = pathinfo($path2, PATHINFO_EXTENSION);
    $contentBefore = file_get_contents($path1);
    $contentAfter = file_get_contents($path2);
    $dataBefore = parse($contentBefore, $fileBeforeFormat);
    $dataAfter = parse($contentAfter, $fileAfterFormat);
    $diffTree = getDiffTree($dataBefore, $dataAfter);
    return getFormatedDiff($diffTree, $format);
}

function getDiffTree($dataBefore, $dataAfter)
{
    $keysBefore = array_keys($dataBefore);
    $keysAfter = array_keys($dataAfter);
    $commonKeys = array_unique(array_merge($keysBefore, $keysAfter));
    sort($commonKeys);
    return array_map(function ($key) use ($dataBefore, $dataAfter) {
        if (!array_key_exists($key, $dataBefore)) {
            return ['key' => $key,
                    'status' => 'added',
                    'value' => $dataAfter[$key]];
        }
        if (!array_key_exists($key, $dataAfter)) {
            return ['key' => $key,
                    'status' => 'deleted',
                    'value' => $dataBefore[$key]];
        }
        if ($dataBefore[$key] === $dataAfter[$key]) {
            return ['key' => $key,
                    'status' => 'unchanged',
                    'value' => $dataBefore[$key]];
        }
        if (is_array($dataBefore[$key]) && is_array($dataAfter[$key])) {
            return ['key' => $key,
                    'status' => 'nested',
                    'children' => getDiffTree($dataBefore[$key], $dataAfter[$key])];
        } else {
            return ['key' => $key,
                    'status' => 'changed',
                    'valueBefore' => $dataBefore[$key],
                    'valueAfter' => $dataAfter[$key]];
        }
    }, $commonKeys);
}

function getFormatedDiff($diffTree, $format)
{
    switch ($format) {
        case 'json':
            return makeJson($diffTree);
        case 'pretty':
            return makePretty($diffTree);
        case 'plain':
            return makePlain($diffTree);
        default:
            throw new \Exception("{$format} is unsupported format");
    }
}
