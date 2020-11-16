<?php

namespace Gendiff\Formatters\plainFormat;

function makePlain($diffTree, $path = [], $result = [])
{
        $plainData =  array_reduce($diffTree, function ($acc, $node) use ($path) {
            $status = $node['status'];
            $key = $node['key'];
            $path[] = $key;
            if ($status !== 'nested') {
                $path = implode('.', $path);
            }
            switch ($status) {
                case 'added':
                    $value = convertValueToPlainString($node['value']);
                    $acc[] = "Property '{$path}' was added with value: {$value}";
                    return $acc;
                case 'deleted':
                    $acc[] = "Property '{$path}' was removed";
                    return $acc;
                case 'unchanged':
                    return $acc;
                case 'changed':
                    $valueBefore = convertValueToPlainString($node['valueBefore']);
                    $valueAfter = convertValueToPlainString($node['valueAfter']);
                    $acc[] = "Property '{$path}' was updated. From {$valueBefore} to {$valueAfter}";
                    return $acc;
                case 'nested':
                    $children = $node['children'];
                    $acc[] = makePlain($children, $path, []);
                    return $acc;
                default:
                    throw new \Exception("Unsupported <{$status}> status in diffTree");
            }
        }, $result);
        return implode("\n", $plainData);
}

function convertValueToPlainString($value)
{
    if (is_bool($value)) {
        return $value ? 'true' : 'false';
    }
    if (!is_array($value)) {
        return "'{$value}'";
    } else {
        return "[complex value]";
    }
}
