<?php

namespace Gendiff\Tests\DifferTest;

use PHPUnit\Framework\TestCase;

use function Gendiff\Src\Differ\getData;
use function Gendiff\Src\Differ\gendiff;

class DifferTest extends TestCase
{
    public function testGendiff()
    {
        $path1 = '/home/eriocl/php-project-lvl2/tests/fixtures/file1.json';
        $path2 = '/home/eriocl/php-project-lvl2/tests/fixtures/file2.json';
        $data = getData($path1, $path2);
        $actual = gendiff($data);
        $expected = "- follow: false\n  host: hexlet.io\n- proxy: 123.234.53.22" .
        "\n- timeout: 50\n+ timeout: 20\n+ verbose: true\n";
        $this->assertEquals($expected, $actual);
    }
}
