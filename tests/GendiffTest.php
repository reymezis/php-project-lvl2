<?php

namespace diff\tests\GendiffTest;

use PHPUnit\Framework\TestCase;

use function Differ\Differ\genDiff;

class GendiffTest extends TestCase
{
    public function testGendiff()
    {
        $jsonFile1 =  __DIR__ . "/fixtures/file1.json";
        $jsonFile2 =  __DIR__ . "/fixtures/file2.json";

        $yamlFile1 =  __DIR__ . "/fixtures/file1.yaml";
        $yamlFile2 =  __DIR__ . "/fixtures/file2.yaml";
        $nestResult = file_get_contents(__DIR__ . "/fixtures/nested_result");
        $this->assertSame(genDiff($yamlFile1, $yamlFile2), $nestResult);
        $this->assertSame(genDiff($jsonFile1, $jsonFile2), $nestResult);

        $plainResult = file_get_contents(__DIR__ . "/fixtures/plain");
        $this->assertSame(genDiff($yamlFile1, $yamlFile2, "plain"), $plainResult);
        $this->assertSame(genDiff($jsonFile1, $jsonFile2, "plain"), $plainResult);

        $jsonResult = file_get_contents(__DIR__ . "/fixtures/json");
        $this->assertSame(genDiff($jsonFile1, $jsonFile2, "json"), $jsonResult);
        $this->assertSame(genDiff($yamlFile1, $yamlFile2, "json"), $jsonResult);
    }
}
