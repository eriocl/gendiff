<?php

namespace Gendiff\Formatters\PlainFormat;

function makePlain($diffTree)
{
    $iter = function ($diffTree, $path = []) use (&$iter) {
        $plainData = array_reduce($diffTree, function ($acc, $node) use ($path, $iter) {
            $status = $node['status'];
            $key = $node['key'];
            $path[] = $key;
            if ($status !== 'nested') {
                $path = implode('.', $path);
            }
            switch ($status) {
                case 'added':
                    $value = stringify($node['value']);
                    $acc[] = "Property '{$path}' was added with value: {$value}";
                    return $acc;
                case 'deleted':
                    $acc[] = "Property '{$path}' was removed";
                    return $acc;
                case 'unchanged':
                    return $acc;
                case 'changed':
                    $valueBefore = stringify($node['valueBefore']);
                    $valueAfter = stringify($node['valueAfter']);
                    $acc[] = "Property '{$path}' was updated. From {$valueBefore} to {$valueAfter}";
                    return $acc;
                case 'nested':
                    $children = $node['children'];
                    $acc[] = $iter($children, $path);
                    return $acc;
                default:
                    throw new \Exception("Unsupported <{$status}> status in diffTree");
            }
        }, []);
        return implode("\n", $plainData);
    };
    return $iter($diffTree);
}

function stringify($value)
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
