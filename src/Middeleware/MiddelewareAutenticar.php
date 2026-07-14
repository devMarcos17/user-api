<?php

namespace Marcos\Middeleware;

use Firebase\JWT\ExpiredException;
use Exception;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(__DIR__ . "/../../");
$dotenv->load();
class MiddelewareAutenticar
{
      public static function validarAutenticao(): array
    {
        if (!isset($_SERVER["HTTP_AUTHORIZATION"])) {
            http_response_code(401);
            echo json_encode(["ERRO" => "Token de acesso não fornecido."]);
            die;
        }
        
        $key = $_ENV["API_KEY"];

        $autorizacao = $_SERVER["HTTP_AUTHORIZATION"];
        $token = str_replace("Bearer ", "", $autorizacao);
        try{
            $decode = JWT::decode($token, new Key($key, "HS256"));
            return (array) $decode;
        }catch(ExpiredException){
            http_response_code(401);
            die(json_encode(["ERRO" => "A sua sessão expirou. Por favor, faça login novamente."]));
        }catch(Exception){
            http_response_code(401);
            die(json_encode(["ERRO" => "Token de acesso inválido ou alterado."]));
        }
    }
}