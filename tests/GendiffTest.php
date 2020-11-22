<?php

namespace diff\tests\GendiffTest;

use PHPUnit\Framework\TestCase;

use function diff\gendiff\genDiff;

class GendiffTest extends TestCase
{
    public function testGendiff()
    {
        $result = <<<EOT
{
    host: hexlet.io
  - timeout: 50
  + timeout: 20
  - proxy: 123.234.53.22
  - follow: false
  + verbose: true
}

EOT;
        $path1 = __DIR__ . "/fixtures/file1.json";
        $path2 = __DIR__ . "/fixtures/file2.json";
        $this->assertSame(genDiff($path1, $path2), $result);

        $yamlFilePath1 =  __DIR__ . "/fixtures/filepath1.yml";
        $yamlFilePath2 =  __DIR__ . "/fixtures/filepath2.yml";
        $this->assertSame(genDiff($yamlFilePath1, $yamlFilePath2), $result);
    }
}
