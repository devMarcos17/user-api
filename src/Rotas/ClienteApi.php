<?php

use Marcos\Controle\ControleCliente;
use Marcos\Middeleware\MiddelewareAutenticar as MiddelewareMiddelewareAutenticar;

require_once __DIR__ . "/../../vendor/autoload.php";
require_once __DIR__ . "/../../config/conexao.php";

session_start();

global $api, $acao, $parametro, $metodo;

$controle = new ControleCliente($conexao);

if($api === "api" && $acao === "clientes"){
    if($metodo === "GET" && $parametro === ""){
        $controle->listar();
        die;
    }
    
    if($metodo === "GET" && $parametro !== ""){
        $controle->listarClienteId($parametro);
        die;
    }
    
    if($metodo === "POST" && $parametro === ""){
        $controle->cadastrar();
        die;
    }
    
    if($metodo === "PUT" && $parametro !== ""){
        MiddelewareMiddelewareAutenticar::validarAutenticao();
        
        if($_SESSION["role"] === "admin"){
            $controle->atualizar($parametro);
        } else {
            http_response_code(403);
            echo json_encode(["erro" => "Não autorizado. Acesso restrito a administradores."]);
        }
        die;
    }
    
    if($metodo === "DELETE" && $parametro !== ""){
       MiddelewareMiddelewareAutenticar::validarAutenticao();
       
       if($_SESSION["role"] === "admin"){
           $controle->deletar($parametro);
       } else {
           http_response_code(403);
           echo json_encode(["erro" => "Não autorizado. Acesso restrito a administradores."]);
       }
       die;
    }
}

if($api === "api" && $acao === "login"){
    if($metodo === "POST" && $parametro === ""){
        $controle->gerarToken();
        die;
    }    
}

if($api === "api" && $acao === "sistema"){
    if($metodo === "GET" && $parametro === ""){
        MiddelewareMiddelewareAutenticar::validarAutenticao();
        
        if($_SESSION["role"] === "admin"){
            echo json_encode(["sucesso" => "Bem-vindo, " . $_SESSION["nome"]]);
        } else {
            http_response_code(403);
            echo json_encode(["erro" => "Não autorizado. Acesso restrito a administradores."]);
        }
        die;
    }    
}