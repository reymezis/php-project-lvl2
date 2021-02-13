<?php

namespace Differ\tests\GendiffTest;

use PHPUnit\Framework\TestCase;

use function Differ\Differ\genDiff;

class GendiffTest extends TestCase
{
    /**
     * @dataProvider  genDiffDataProvider
     */
    public function testGendiff($expected, $argument1, $argument2, $argument3 = "stylish")
    {
        $this->assertEquals($expected, genDiff($argument1, $argument2, $argument3));
    }
    public function genDiffDataProvider()
    {
        $pathToJsonFile1 = $this->getFixturePath("file1.json");
        $pathToJsonFile2 = $this->getFixturePath("file2.json");

        $pathToYamlFile1 = $this->getFixturePath("file1.yaml");
        $pathToYamlFile2 = $this->getFixturePath("file2.yaml");

        $plainFormatterName = "plain";
        $jsonFormatterName = "json";
        $stylishFormatterName = "stylish";

        $nestResult = file_get_contents($this->getFixturePath("nested_result"));
        $plainResult = file_get_contents($this->getFixturePath("plain_result"));
        $jsonResult = file_get_contents($this->getFixturePath("json_result"));


        return [
            [
                $nestResult,
                $pathToJsonFile1,
                $pathToJsonFile2
            ],
            [
                $nestResult,
                $pathToYamlFile1,
                $pathToYamlFile2,
            ],
            [
                $plainResult,
                $pathToYamlFile1,
                $pathToYamlFile2,
                $plainFormatterName
            ],
            [
                $jsonResult,
                $pathToYamlFile1,
                $pathToYamlFile2,
                $jsonFormatterName
            ],
            [
                $nestResult,
                $pathToYamlFile1,
                $pathToYamlFile2,
                $stylishFormatterName
            ]
        ];
    }

    private function getFixturePath($fileName)
    {
        return __DIR__ . "/fixtures/{$fileName}";
    }
}
