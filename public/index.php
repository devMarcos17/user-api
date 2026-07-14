<?php

header('Content-Type: application/json; charset=UTF-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');


if(isset($_GET["path"])){
    $path = explode("/", $_GET["path"]);
}



if(isset($path[0])) {$api = $path[0];} else {die("CAMINHO INEXISTENTE");}

if(isset($path[1])) {$acao = $path[1];} else {$acao = "";}

if(isset($path[2])) {$parametro = $path[2];} else {$parametro = "";}


$metodo = $_SERVER["REQUEST_METHOD"];

require_once __DIR__ . "/../src/Rotas/ClienteApi.php";