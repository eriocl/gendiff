<?php

namespace Gendiff\Parsers;

use Symfony\Component\Yaml\Yaml;

function parse($data, $format)
{
    switch ($format) {
        case "json":
            return json_decode($data, true);
        case "yaml":
            return Yaml::parse($data);
        default:
            throw new \Exception("Format {$format} is not supported");
    }
}
