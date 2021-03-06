<?php

namespace Gendiff\Formatters\PrettyFormat;

function makePretty($diffTree)
{
    $iter = function ($diffTree, $depth = 0) use (&$iter) {
        $tab = str_repeat('    ', $depth);
        $statusOperators = ['deleted' => '-', 'unchanged' => ' ', 'added' => '+'];
        $formatedTree = array_map(function ($node) use ($tab, $depth, $statusOperators, $iter) {
            $status = $node['status'];
            $key = $node['key'];
            switch ($status) {
                case 'deleted':
                case 'unchanged':
                case 'added':
                    $value = convertValueToPrettyString($node['value'], $key, $depth + 1);
                    return "{$tab}  {$statusOperators[$status]} {$value}";
                case 'changed':
                    $valueBefore = convertValueToPrettyString($node['valueBefore'], $key, $depth + 1);
                    $valueAfter = convertValueToPrettyString($node['valueAfter'], $key, $depth + 1);
                    return "{$tab}  - {$valueBefore}\n" . "{$tab}  + {$valueAfter}";
                case 'nested':
                    $children = $node['children'];
                    return "    {$tab}{$key}: {\n" . $iter($children, $depth + 1) . "\n    {$tab}}";
                default:
                    throw new \Exception("Unsupported <{$status}> status in diffTree");
            }
        }, $diffTree);
        return implode("\n", $formatedTree);
    };
    return "{\n" . $iter($diffTree) . "\n}";
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
