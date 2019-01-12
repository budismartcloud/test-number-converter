<?php
/**
 * Created by PhpStorm.
 * User: lenovo
 * Date: 12/01/2019
 * Time: 20:46
 */

namespace App\BusinessLayer;


use App\DTO\WordDTO;
use App\PresentationLayer\ResponseAttributePresentationLayer;

class WordConverterBusinessLayer
{

    private $validString;

    public function __construct()
    {
        $this->validString = array(
            'nol'      => '0',
            'satu'     => '1',
            'se'       => '1',
            'dua'       => '2',
            'tiga'     => '3',
            'empat'      => '4',
            'lima'      => '5',
            'enam'       => '6',
            'tujuh'     => '7',
            'delapan'     => '8',
            'sembilan'      => '9',
            'sepuluh'       => '10',
            'sebelas'    => '11',
            'dua belas'    => '12',
            'tiga belas'  => '13',
            'empat belas'  => '14',
            'lima belas'   => '15',
            'enam belas'   => '16',
            'tujuh belas' => '17',
            'delapan belas'  => '18',
            'sembilan belas'  => '19',
            'dua puluh'    => '20',
            'tiga puluh'    => '30',
            'empat puluh'     => '40',
            'lima puluh'     => '50',
            'enam puluh'     => '60',
            'tujuh puluh'   => '70',
            'delapan puluh'    => '80',
            'sembilan puluh'    => '90',
            'seratus'   => '100',
            'ratus' => '100',
            'seribu'  => '1000.1',
            'ribu' => '1000',
            'satu juta'   => '1000000.1',
            'juta' => '1000000',
            'satu milyar'   => '1000000000.1',
            'milyar' => '1000000000'
        );
    }

    public function actionConvert(WordDTO $params)
    {
        try{
            $data = strtr(
                $params->getKeyword(),
                $this->validString
            );

            $parts = array_map(
                function ($val) {
                    return floatval($val);
                },
                preg_split('/[\s-]+/', $data)
            );

            $stack = new \SplStack();
            $sum   = 0;
            $last  = null;

            foreach ($parts as $part) {
                if (!$stack->isEmpty()) {
                    if ($stack->top() > $part) {
                        if ($last >= 1000) {
                            $sum += $stack->pop();
                            $stack->push($part);
                        } else {
                            $stack->push($stack->pop() + $part);
                        }

                    } else {
                        $decimal = explode(".", $part);
                        $stack->push($stack->pop() * intval($part));
                        if(isset($decimal[1]) && $decimal[1] > 0){
                            $stack->push($stack->pop() + intval($part));
                        }
                    }
                } else {
                    $stack->push($part);
                }

                $last = $part;
            }

            $result = $sum + $stack->pop();
            $response = new ResponseAttributePresentationLayer(200, 'Data berhasil ditemukan', number_format($result));
        }catch (\Exception $e){
            $data = null;
            $response = new ResponseAttributePresentationLayer(500, 'Terjadi kesalahan pada server', $data);
        }

        return $response->getResponse();
    }

    public function tokenizing(WordDTO $params)
    {
        try{
            $arrayData = explode(" ", $params->getKeyword());
            $invalidSegment = 0;
            $isSkip = 0;
            foreach ($arrayData as $num => $item)
            {
                if(isset($arrayData[$num + 1])){
                    $nextPhrase = $arrayData[$num + 1];
                }else{
                    $nextPhrase = "";
                }
                switch ($item){
                    case 'belas' :
                        if($nextPhrase == "puluh" || $nextPhrase == "ratus"){
                            $invalidSegment++;
                        }
                        break;
                    case 'puluh' :
                        if($nextPhrase == "belas" || $nextPhrase =="ratus"){
                            $invalidSegment++;
                        }
                        break;
                    case 'ratus' :
                        if($nextPhrase == "belas" || $nextPhrase =="ratus"){
                            $invalidSegment++;
                        }
                        break;
                    case 'ribu' :
                        if($nextPhrase == "belas" || $nextPhrase =="ratus"){
                            $invalidSegment++;
                        }
                        break;
                    case 'juta':
                        if($nextPhrase == "belas" || $nextPhrase =="ratus"){
                            $invalidSegment++;
                        }
                        break;
                }

                if($isSkip == 1){
                    continue;
                }

                switch ($nextPhrase) {
                    case 'belas' :
                        $key = $item." ".$nextPhrase;
                        if(!isset($this->validString[$key])){
                            $invalidSegment++;
                            $isSkip = 0;
                        }else{
                            $isSkip = 1;
                        }
                        break;
                    case 'puluh':
                        $key = $item." ".$nextPhrase;
                        if(!isset($this->validString[$key])){
                            $invalidSegment++;
                            $isSkip = 0;
                        }else{
                            $isSkip = 1;
                        }

                        break;
                    case 'milyar' :
                        $invalidSegment = 1;
                        $isSkip = 0;
                        break;
                    default :
                        if(!isset($this->validString[$item])){
                            $invalidSegment++;
                        }
                        $isSkip = 0;
                        break;
                }

            }


            $data = $invalidSegment;
            $response = new ResponseAttributePresentationLayer(200, 'Operasi berhasil', $data);
        }catch (\Exception $e){
            $data = null;
            $response = new ResponseAttributePresentationLayer(500, 'Terjadi kesalahan pada server', $data);
        }
        return $response->getResponse();
    }
}
