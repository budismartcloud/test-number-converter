<?php
/**
 * Created by PhpStorm.
 * User: lenovo
 * Date: 12/01/2019
 * Time: 20:06
 */

namespace App\Http\Controllers;


use App\BusinessLayer\WordConverterBusinessLayer;
use App\DTO\WordDTO;
use Illuminate\Http\Request;

class HomeController extends Controller
{

    private $wordConverterBusinessLayer;

    public function __construct()
    {
        $this->wordConverterBusinessLayer = new WordConverterBusinessLayer();
    }

    public function index(Request $request)
    {
        $params = [
            'title' => 'Number Converter'
        ];
        return view('home.index', $params);
    }

    public function convert(Request $request)
    {
        $wordDTO = new WordDTO();
        $wordDTO->setKeyword($keyword = strtolower($request->input('keyword')));
        $invalidNumber = $this->wordConverterBusinessLayer->tokenizing($wordDTO);

        if($invalidNumber['code'] != 200){
            return $invalidNumber['message'];
        }

        if($invalidNumber['data'] > 0){
            return "Invalid";
        }

        $result = $this->wordConverterBusinessLayer->actionConvert($wordDTO);
        if($invalidNumber['code'] != 200){
            return $invalidNumber['message'];
        }

        return $result['data'];

    }
}
