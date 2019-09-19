<?php

namespace Labirinto;

class Individuo
{

    private $genes = '';
    function setGenes(string $genes)
    {
        $this->genes = $genes;
        $this->calculaAptidao();
    }
    function getGenes(): string
    {
        return $this->genes;
    }

    function __construct()
    {
    }

    private $aptidao = 0;
    function getAptidao(): int
    {
        return $this->aptidao;
    }

    private function calculaAptidao()
    {
        $aptidao = 0;

        $passou = [];

        $x = 0;
        $y = 9;
        $fx = 0;
        $fy = 9;
        $p = 0;

        for($i=0; $i<(strlen($this->genes)/2); $i++){

            $op = substr($this->genes, $i*2, 2);

                /**
                 * 00 - RIGHT
                 * 01 - UP
                 * 10 - LEFT
                 * 11 - DOWN
                 */

            switch($op){
                case '00': 
                    $fx++; 
                    $p = 3;
                break;
                case '01':
                    $fy--;
                    $p = 0;
                break;
                case '10':
                    $fx--;
                    $p = 2;
                break;
                case '11':
                    $fy++;
                    $p = 1;
                break;
            }

            if($x < 0 or $x > 9 or $y < 0 or $y > 9){
                $aptidao -= $_GET['fora'];
            }else{
                if(!Tabuleiro::$tabuleiro[$y][$x][$p]){
                    $aptidao -= $_GET['parede'];
                }else{
                    $repetido = false;

                    foreach($passou as $p){
                        if($p[0] == $fx && $p[1] == $fy){
                            $repetido = true;
                            $aptidao -= $_GET['repeticao'];
                        }
                    }

                    if(!$repetido){

                        switch($op){
                            case '00': 
                                $aptidao += $_GET['direita'];
                            break;
                            case '01':
                                $aptidao += $_GET['cima'];
                            break;
                            case '10':
                                $aptidao += $_GET['esquerda'];
                            break;
                            case '11':
                                $aptidao += $_GET['baixo'];
                            break;
                        }

                    } 
                }
            }

            $x = $fx;
            $y = $fy;

            if($x == 9 && $y == 0){
                $aptidao += $_GET['final'];
            }
            $passou[] = [$x,$y];
        }

        $this->aptidao = $aptidao;
    }


}