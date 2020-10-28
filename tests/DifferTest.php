<?php

namespace Gendiff\Tests\DifferTest;

use PHPUnit\Framework\TestCase;

use function Gendiff\Src\Differ\getData;
use function Gendiff\Src\Differ\gendiff;

class DifferTest extends TestCase
{
    public function testGendiff(): void
    {
        $file1 = 'file1.json';
        $file2 = 'file2.json';
        $path1 = implode(DIRECTORY_SEPARATOR, [__DIR__,'fixtures',$file1]);
        $path2 = implode(DIRECTORY_SEPARATOR, [__DIR__,'fixtures',$file2]);
        $data = getData($path1, $path2);
        $actual = gendiff($data);
        $expected = "- follow: false\n  host: hexlet.io\n- proxy: 123.234.53.22" .
        "\n- timeout: 50\n+ timeout: 20\n+ verbose: true\n";
        $this->assertEquals($expected, $actual);
    }
}
