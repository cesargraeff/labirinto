<?php

namespace Labirinto;

set_time_limit(3600);

include 'classes/Tabuleiro.php';
include 'classes/Individuo.php';
include 'classes/Populacao.php';
include 'classes/Algoritmo.php';

$algoritmo = new Algoritmo();
$algoritmo->setTamPopulacao($_GET['populacao']);
$algoritmo->setMaxGeracoes($_GET['geracoes']);
$algoritmo->setMutacao($_GET['mutacao']);
$algoritmo->setCrossover($_GET['mutacao']);
$algoritmo->setIndividuos($_GET['individuos']);
$algoritmo->setElitismo(isset($_GET['elitismo']) && $_GET['elitismo'] == 'on');

$algoritmo->run();

echo json_encode([
    'tabuleiro' => Tabuleiro::$tabuleiro,
    'historico' => $algoritmo->getHistorico()
]);
