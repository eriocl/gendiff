<?php

namespace Gendiff\src\differ;

function getData($pathToFile1, $pathToFile2)
{
    $result = [];
    $data1 = file_get_contents($pathToFile1);
    $data2 = file_get_contents($pathToFile2);
    $dcodeData1 = json_decode($data1, true);
    $dcodeData2 = json_decode($data2, true);
    array_push($result, $dcodeData1, $dcodeData2);
    return $result;
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

function gendiff($data)
{
    [$data1, $data2] = $data;
    $data1 = convertValuesToString($data1);
    $data2 = convertValuesToString($data2);
    $keysData1 = array_keys($data1);
    $keysData2 = array_keys($data2);
    $deletedData = array_reduce($keysData1, function ($acc, $key) use ($data1, $data2) {
        if (!array_key_exists($key, $data2)) {
            $acc .= "- {$key}: {$data1[$key]}\n";
        }
        return $acc;
    }, '');
    $addedData = array_reduce($keysData2, function ($acc, $key) use ($data1, $data2) {
        if (!array_key_exists($key, $data1)) {
            $acc .= "+ {$key}: {$data2[$key]}\n";
        }
        return $acc;
    }, '');
    $changedData = array_reduce($keysData2, function ($acc, $key) use ($data1, $data2) {
        if (array_key_exists($key, $data1)) {
            $acc .= $data1[$key] === $data2[$key]
                ? "  {$key}: {$data2[$key]}\n"
                : "- {$key}: {$data1[$key]}\n+ {$key}: {$data2[$key]}\n";
        }
        return $acc;
    }, '');
    return $deletedData . $addedData . $changedData;
}
