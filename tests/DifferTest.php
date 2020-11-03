<?php

namespace Gendiff\Tests\DifferTest;

use PHPUnit\Framework\TestCase;

use function Gendiff\Src\Differ\getData;
use function Gendiff\Src\Differ\gendiff;

class DifferTest extends TestCase
{
    /**
     * @dataProvider additionProvider
     */

    public function testGendiff($fileBefore, $fileAfter): void
    {
        $pathBefore = $this->getFixturePath($fileBefore);
        $pathAfter = $this->getFixturePath($fileAfter);
        $actual = gendiff($pathBefore, $pathAfter);
        $expected = file_get_contents(implode(DIRECTORY_SEPARATOR, [__DIR__,'fixtures','result']));
        $this->assertEquals($expected, $actual);
    }

    public function getFixturePath($fixtureName)
    {
        return implode(DIRECTORY_SEPARATOR, [__DIR__,'fixtures',$fixtureName]);
    }

    public function additionProvider()
    {
        return [
            ['before.json', 'after.json'],
            ['before.yaml', 'after.yaml']
        ];
    }
}
