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
    $keys= array_unique(array_merge($keysData1, $keysData2));
    sort($keys);
    return array_reduce($keys, function ($acc, $key) use ($data1, $data2) {
        if (array_key_exists($key, $data1) && !array_key_exists($key, $data2)) {
            $acc .= "- {$key}: {$data1[$key]}\n";
        }
        if (!array_key_exists($key, $data1) && array_key_exists($key, $data2)) {
            $acc .= "+ {$key}: {$data2[$key]}\n";
        }
        if (array_key_exists($key, $data1) && array_key_exists($key, $data2)) {
            if ($data1[$key] === $data2[$key]) {
                $acc .= "  {$key}: {$data2[$key]}\n";
            } else {
                $acc .= "- {$key}: {$data1[$key]}\n+ {$key}: {$data2[$key]}\n";
            }
        }
        return $acc;
    }, '');
}
