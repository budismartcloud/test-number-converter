<?php

namespace Tests\Feature;

use App\BusinessLayer\WordConverterBusinessLayer;
use App\DTO\WordDTO;
use Tests\TestCase;

class ConverterUnitTest extends TestCase
{
    public function testCaseOne()
    {
        $wordConverterBusinessLayer = new WordConverterBusinessLayer();
        $params = new WordDTO();
        $params->setKeyword("tiga ratus lima puluh satu juta delapan ribu sembilan ratus empat");

        $tokenizingResult = $wordConverterBusinessLayer->tokenizing($params);
        $this->assertSame(0, $tokenizingResult['data']);

        $result = $wordConverterBusinessLayer->actionConvert($params);
        $this->assertSame(number_format(351008904), $result['data']);

    }

    public function testCaseTwo()
    {
        $wordConverterBusinessLayer = new WordConverterBusinessLayer();
        $params = new WordDTO();
        $params->setKeyword("tujuh puluh tiga ribu delapan belas ratus lima puluh sembilan");

        $tokenizingResult = $wordConverterBusinessLayer->tokenizing($params);
        $this->assertSame(1, $tokenizingResult['data']);

    }

    public function testCaseThree()
    {
        $wordConverterBusinessLayer = new WordConverterBusinessLayer();
        $params = new WordDTO();
        $params->setKeyword("dua puluh tiga ribu lima ratus tiga puluh dua");

        $tokenizingResult = $wordConverterBusinessLayer->tokenizing($params);
        $this->assertSame(0, $tokenizingResult['data']);

        $result = $wordConverterBusinessLayer->actionConvert($params);
        $this->assertSame(number_format(23532), $result['data']);

    }

}
