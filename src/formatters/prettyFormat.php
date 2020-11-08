<?php

namespace Gendiff\Src\Formatters\Pretty;

function makePretty($diffTree, $depth = 0)
{
    $tab = str_repeat('    ', $depth);
    $formatedTree = array_map(function ($node) use ($tab, $depth) {
        $status = $node['status'];
        $key = $node['key'];
        if ($status !== 'nested' && $status !== 'changed') {
            $value = convertValueToPrettyString($node['value'], $key, $depth + 1);
        }
        switch ($status) {
            case 'added':
                return "{$tab}  + {$value}";
            case 'deleted':
                return "{$tab}  - {$value}";
            case 'unchanged':
                return "{$tab}    {$value}";
            case 'changed':
                $valueBefore = convertValueToPrettyString($node['valueBefore'], $key, $depth + 1);
                $valueAfter = convertValueToPrettyString($node['valueAfter'], $key, $depth + 1);
                return "{$tab}  - {$valueBefore}\n" . "{$tab}  + {$valueAfter}" ;
            case 'nested':
                $children = $node['children'];
                return "    {$tab}{$key}: {\n" . makePretty($children, $depth + 1) . "\n    {$tab}}";
            default:
                throw new \Exception("Unsupported <{$status}> status in diffTree");
        }
    }, $diffTree);
    if ($depth === 0) {
        return "{\n" . implode("\n", $formatedTree) . "\n}\n";
    } else {
        return implode("\n", $formatedTree);
    }
}

function convertValueToPrettyString($value, $key, $depth)
{
    $tab = str_repeat('    ', $depth);
    if (is_bool($value)) {
        $value =  $value ? 'true' : 'false';
        return "{$key}: {$value}";
    }
    if (!is_array($value)) {
        return "{$key}: {$value}";
    } else {
        $keys = array_keys($value);
        $formattedValue = array_map(function ($key) use ($depth, $value) {
            $tab = str_repeat('    ', $depth + 1);
            return  "{$tab}" . convertValueToPrettyString($value[$key], $key, $depth + 1);
        }, $keys);
        return "{$key}: {\n" . implode("\n", $formattedValue) . "\n{$tab}}";
    }
}
