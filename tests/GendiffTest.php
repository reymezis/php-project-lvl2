<?php

namespace diff\tests\GendiffTest;

use PHPUnit\Framework\TestCase;

use function diff\gendiff\genDiff;

class GendiffTest extends TestCase
{
    public function testGendiff()
    {
        $yamlFile1 =  __DIR__ . "/fixtures/file1.yml";
        $yamlFile2 =  __DIR__ . "/fixtures/file2.yml";
        $nestResult = file_get_contents(__DIR__ . "/fixtures/nested_result");
        $this->assertSame(genDiff($yamlFile1, $yamlFile2), $nestResult);
    }
}
