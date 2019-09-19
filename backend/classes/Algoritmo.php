<?php

namespace Labirinto;

class Algoritmo
{

    private $tampopulacao;
    function setTamPopulacao(int $tampopulacao)
    {
        if($tampopulacao % 2 == 1)
            $tampopulacao++;
        
        $this->tampopulacao = $tampopulacao;
    }

    private $maxgeracoes;
    function setMaxGeracoes(int $geracoes)
    {
        $this->maxgeracoes = $geracoes;
    }

    private $mutacao;
    function setMutacao(int $mutacao)
    {
        $this->mutacao = $mutacao;
    }

    private $crossover;
    function setCrossover(int $crossover)
    {
        $this->crossover = $crossover;
    }

    private $individuos;
    function setIndividuos(int $individuos)
    {
        $this->individuos = $individuos;
    }

    private $elitismo = true;
    function setElitismo(bool $elitismo)
    {
        $this->elitismo = $elitismo;
    }

    private $historico = [];
    function getHistorico() : array
    {
        return $this->historico;
    }

    private $geracoes = 0;
    function getGeracoes()
    {
        return $this->geracoes;
    }

    private $populacao;

    /**
     * 00 - RIGHT
     * 01 - UP
     * 10 - LEFT
     * 11 - DOWN
     */

    function run()
    {
        $this->populacao = new Populacao($this->tampopulacao, 54);
        $this->populacao->ordena();

        $solucao = false;
        $geracoes = 0;
        $gene = '';

        //$novo = new Individuo();
        //$novo->setGenes('010101000001010001101010010000000000010100000011000100');
        //$this->populacao->addIndividuo($novo);
        //var_dump($novo->getAptidao());
        //gene = $novo->getGenes();

        while(!$solucao && $geracoes < $this->maxgeracoes){

            $geracoes++;

            $this->populacao = $this->novaGeracao($this->populacao);
            $this->populacao->ordena();

            $melhor = $this->populacao->getMelhorIndividuo();
            $this->historico[] = [
                'geracao' => $geracoes,
                'genes' => $melhor->getGenes(),
                'aptidao' => $melhor->getAptidao()
            ];

            $gene = $melhor->getGenes();

            $solucao = $this->populacao->temSolucao();
            if($solucao != null){
                $gene = $solucao->getGenes();
            }
        }

        $this->montaSolucao($gene);
    }

    private function novaGeracao(Populacao $pop): Populacao
    {
        $new = new Populacao();

        if($this->elitismo){
            $elite = $pop->getElite();
        }
        
        while($new->getTamanho() <= ($pop->getTamanho() - (isset($elite) ? count($elite) : 0))){

            $novos = $this->torneio();

            if(mt_rand(0,100) < $this->crossover){
                $novos = $this->crossover($novos[0], $novos[1]);
            }

            if(mt_rand(0,100) < $this->mutacao){
                $this->mutacao($novos[0]);
                $this->mutacao($novos[1]);
            }

            $new->addIndividuo($novos[0]);
            $new->addIndividuo($novos[1]);
        }
        
        if($this->elitismo){
            foreach($elite as $ind){
                $new->addIndividuo($ind);
            }
        }

        
        return $new;
    }

    private function torneio()
    {
        $selecao = new Populacao();
        
        for($i=0; $i< $this->individuos; $i++){
            $selecao->addIndividuo(
                $this->populacao->getIndividuo(
                    mt_rand(0,$this->tampopulacao - 1)
                )
            );
        }

        $selecao->ordena();

        return [
            $selecao->getIndividuo(0),
            $selecao->getIndividuo(1),
        ];
    }

    private function crossover(Individuo $pai, Individuo $mae)
    {
        $f1 = new Individuo();
        $f2 = new Individuo();

        $g1 = $pai->getGenes();
        $g2 = $mae->getGenes();

        $tam = ceil(strlen($g1) / 4);

        $f1->setGenes(substr($g1,0,$tam).substr($g2,$tam,$tam).substr($g1,$tam*2,$tam).substr($g1,$tam*3));
        $f2->setGenes(substr($g2,0,$tam).substr($g1,$tam,$tam).substr($g2,$tam*2,$tam).substr($g2,$tam*3));

        return [$f1, $f2];
    }

    private function mutacao(Individuo $individuo)
    {
        $gene = $individuo->getGenes();

        $indice = mt_rand(0,strlen($gene)-1);
        $bit = substr($gene,$indice,1);

        $individuo->setGenes(
            substr_replace($gene, $bit == '0' ? '1' : '0', $indice, 1)
        );

    }

    private function montaSolucao(string $solucao)
    {
        $x = 0;
        $y = 9;

        Tabuleiro::$tabuleiro[$y][$x][] = 1;

        for($i=0; $i<(strlen($solucao)/2); $i++){

            $op = substr($solucao, $i*2, 2);

            switch($op){
                case '00': $x++; break;
                case '01': $y--; break;
                case '10': $x--; break;
                case '11': $y++; break;
            }

            Tabuleiro::$tabuleiro[$y][$x][] = 1;
        }
    }

}