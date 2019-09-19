<?php

namespace Labirinto;

class Populacao
{

    public $individuos = [];
    function addIndividuo(Individuo $individuo)
    {
        $this->individuos[] = $individuo;
    }
    function getIndividuo(int $index): Individuo
    {
        return $this->individuos[$index];
    }
    function getTamanho(): int
    {
        return count($this->individuos);
    }

    function getElite(){
        $arr = [];

        $mudou = false;

        $this->ordena();

        $arr[] = $this->getIndividuo(0);
        $aptidao = $this->getIndividuo(0)->getAptidao();
        $i = 1;

        while(!$mudou && $i < 5){
            $ind = $this->getIndividuo($i);
            if($aptidao === $ind->getAptidao()){
                $arr[] = $ind;
            }else{
                $mudou = true;
            }
            $i++;
        }

        return $arr;
    }

    function __construct(int $populacao = 0, int $genes = 0)
    {
        $i=0;
        while($i<$populacao){
            
            $gene = str_pad(decbin(mt_rand(0, (pow(2, $genes) - 1))), $genes, '0', STR_PAD_LEFT);

            $igual = false;
            foreach($this->individuos as $a){
                if($a->getGenes() == $gene){
                    $igual = true;
                }
            }

            if(!$igual){
                $ind = new Individuo();
                $ind->setGenes($gene);
                $this->individuos[] = $ind;
                $i++;
            }
        }
    }

    function getMelhorIndividuo(): Individuo
    {
        return $this->individuos[0];
    }

    function ordena()
    {
        usort($this->individuos, function (Individuo $a, Individuo $b){
            $aa = $a->getAptidao();
            $ab = $b->getAptidao();

            if ($aa == $ab) 
                return 0;

            if ($aa < $ab)
                return 1;
            else 
                return -1;
        });
    }

    function temSolucao()
    {
        foreach($this->individuos as $individuo){
            if($individuo->getAptidao() == 1067){
                return $individuo;
            }
        }
        return null;
    }

}