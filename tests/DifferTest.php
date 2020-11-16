<?php

namespace Gendiff\Tests\differTest;

use PHPUnit\Framework\TestCase;

use function Gendiff\Differ\gendiff;

class DifferTest extends TestCase
{
    /**
     * @dataProvider additionProvider
     */

    public function testGendiff($type, $format): void
    {
        $pathBefore = $this->getFixturePath("before.{$type}");
        $pathAfter = $this->getFixturePath("after.{$type}");
        $actual = gendiff($pathBefore, $pathAfter, $format);
        $expected = file_get_contents(implode('/', [__DIR__,'fixtures',"{$format}Format"]));
        $this->assertEquals($expected, $actual);
    }

    public function getFixturePath($fixtureName)
    {
        return implode('/', [__DIR__,'fixtures',$fixtureName]);
    }

    public function additionProvider()
    {
        return [
            ['json', 'pretty'],
            ['yaml', 'plain'],
            ['json', 'json']
            ];
    }
}
