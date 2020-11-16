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
                    $value = stringify($node['value']);
                    $acc[] = "Property '{$path}' was added with value: {$value}";
                    break;
                case 'deleted':
                    $acc[] = "Property '{$path}' was removed";
                    break;
                case 'unchanged':
                    break;
                case 'changed':
                    $valueBefore = stringify($node['valueBefore']);
                    $valueAfter = stringify($node['valueAfter']);
                    $acc[] = "Property '{$path}' was updated. From {$valueBefore} to {$valueAfter}";
                    break;
                case 'nested':
                    $children = $node['children'];
                    $acc[] = makePlain($children, $path, []);
                    break;
                default:
                    throw new \Exception("Unsupported <{$status}> status in diffTree");
            }
            return $acc;
        }, $result);
        return implode("\n", $plainData);
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
