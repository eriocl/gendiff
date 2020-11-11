<?php

namespace Gendiff\Src\Formatters\Plain;

function makePlain($diffTree, $path = [], $result = '')
{
    $formatedTree = array_reduce($diffTree, function ($acc, $node) use ($path) {
        $status = $node['status'];
        $key = $node['key'];
        $path[] = $key;
        if ($status !== 'nested') {
            $path = implode('.', $path);
        }
        switch ($status) {
            case 'added':
                $value = convertValueToPlainString($node['value']);
                return $acc . "Property '{$path}' was added with value: {$value}\n";
            case 'deleted':
                return $acc . "Property '{$path}' was removed\n";
            case 'unchanged':
                return $acc;
            case 'changed':
                $valueBefore = convertValueToPlainString($node['valueBefore']);
                $valueAfter = convertValueToPlainString($node['valueAfter']);
                return $acc . "Property '{$path}' was updated. From {$valueBefore} to {$valueAfter}\n";
            case 'nested':
                $children = $node['children'];
                return makePlain($children, $path, $acc);
            default:
                throw new \Exception("Unsupported <{$status}> status in diffTree");
        }
    }, $result);
    return $formatedTree;
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
