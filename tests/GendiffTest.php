<?php

namespace Differ\tests\GendiffTest;

use PHPUnit\Framework\TestCase;

use function Differ\Differ\genDiff;

class GendiffTest extends TestCase
{
    /**
     * @dataProvider  additionProvider
     */
    public function testGendiff($expected, $argument1, $argument2, $argument3 = "stylish")
    {
        $this->assertEquals($expected, genDiff($argument1, $argument2, $argument3));
    }
    public function additionProvider()
    {
        $jsonFile1 = $this->getFixturePath("file1.json");
        $jsonFile2 = $this->getFixturePath("file2.json");

        $yamlFile1 = $this->getFixturePath("file1.yaml");
        $yamlFile2 = $this->getFixturePath("file2.yaml");

        $plainFormatter = "plain";
        $jsonFormatter = "json";

        $nestResult = file_get_contents(__DIR__ . "/fixtures/nested_result");
        $plainResult = file_get_contents(__DIR__ . "/fixtures/plain_result");
        $jsonResult = file_get_contents(__DIR__ . "/fixtures/json_result");


        return [
            [
                $nestResult,
                $jsonFile1,
                $jsonFile2
            ],
            [
                $nestResult,
                $yamlFile1,
                $yamlFile2,
            ],
            [
                $plainResult,
                $yamlFile1,
                $yamlFile2,
                $plainFormatter
            ],
            [
                $jsonResult,
                $yamlFile1,
                $yamlFile2,
                $jsonFormatter
            ]
        ];
    }

    private function getFixturePath($fileName)
    {
        return __DIR__ . "/fixtures/{$fileName}";
    }
}
