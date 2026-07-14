<?php

namespace Marcos\Controle;

use Firebase\JWT\ExpiredException;
use Firebase\JWT\JWT;
use PDO;
use Marcos\Modelo\Cliente;
use Marcos\Servico\ServicoCliente;
use Throwable;

use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(__DIR__ . "/../../");
$dotenv->load();

session_start();

class ControleCliente
{
    private ServicoCliente $servicoCliente;
    public function __construct(PDO $conexao)
    {
        $this->servicoCliente = new ServicoCliente($conexao);
    }

    public function cadastrar(): void
    {
        $dadoJson = file_get_contents("php://input");
        $dados = json_decode($dadoJson, true);

        if(!filter_var($dados["email"], FILTER_VALIDATE_EMAIL)){
            http_response_code(400);
            echo json_encode(["erro" => "E-mail inválido."]);
            die;
        }
        $email = filter_var($dados["email"], FILTER_SANITIZE_EMAIL);
        $cliente = new Cliente(
            $dados["nome"], $email, $dados["senha"]
        );

        try {
            $cadastrar = $this->servicoCliente->cadastrarCliente($cliente);
            if($cadastrar){
                http_response_code(201);
                echo json_encode(["sucesso" => "Cadastro realizado com sucesso!"]);
                die;
            }
            http_response_code(400);
            echo json_encode(["erro" => "Falha ao realizar o cadastro."]);
            die;
        }catch(Throwable $e){
            http_response_code(500);
            echo json_encode(["erro" => "Ocorreu um erro inesperado no servidor."]);
            die;
        }
    }

    public function atualizar(int $id): void
    {
        $dadoJson = file_get_contents("php://input");
        $dados = json_decode($dadoJson, true);

        if(!filter_var($dados["email"], FILTER_VALIDATE_EMAIL)){
            http_response_code(400);
            echo json_encode(["erro" => "E-mail inválido."]);
            die;
        }
        $email = filter_var($dados["email"], FILTER_SANITIZE_EMAIL);
        $cliente = new Cliente(
            $dados["nome"], $email, $dados["senha"], "user", $id
        );

        try {
            $atualizar = $this->servicoCliente->atualizarCliente($cliente);
            if($atualizar){
                http_response_code(200);
                echo json_encode(["sucesso" => "Atualização realizada com sucesso!"]);
                die;
            }
            http_response_code(400);
            echo json_encode(["erro" => "Falha ao atualizar os dados."]);
            die;
        }catch(Throwable $e){
            http_response_code(500);
            echo json_encode(["erro" => "Ocorreu um erro inesperado no servidor."]);
            die;
        }
    }

    public function deletar(int $id): void
    {
        $cliente = new Cliente("", "", "", "", $id);

        try {
            $deletar = $this->servicoCliente->deletarCliente($cliente);
            if($deletar){
                http_response_code(200);
                echo json_encode(["sucesso"=> "Cliente deletado com sucesso!"]);
                die;
            }
            http_response_code(404);
            echo json_encode(["erro"=> "Cliente não encontrado ou falha ao deletar."]);
            die;
        }catch(Throwable $e){
            http_response_code(500);
            echo json_encode(["erro" => "Ocorreu um erro inesperado no servidor."]);
            die;
        }
    }

    public function listar(): void
    {
        try {
            $listar = $this->servicoCliente->listarClientes();
            if($listar){
                http_response_code(200);
                echo json_encode(["clientes" => $listar]);
                die;
            }
            http_response_code(404);
            echo json_encode(["erro" => "Nenhum cliente encontrado."]);
            die;
        }catch(Throwable $e){
            http_response_code(500);
            echo json_encode(["erro" => "Ocorreu um erro inesperado no servidor."]);
            die;
        }
    }

    public function listarClienteId(int $id): void
    {
        $cliente = new Cliente("", "", "", "", $id);

        try {
            $listarId = $this->servicoCliente->listarCliente($cliente);
            if($listarId){
                http_response_code(200);
                echo json_encode(["cliente" => $listarId]);
                die;
            }
            http_response_code(404);
            echo json_encode(["erro" => "Cliente não encontrado."]);
            die;
        }catch(Throwable $e){
            http_response_code(500);
            echo json_encode(["erro" => "Ocorreu um erro inesperado no servidor."]);
            die;
        }
    }

    public function gerarToken(): void
    {
        $dadosJson = file_get_contents("php://input");
        $dados = json_decode($dadosJson, true);

        if(!filter_var($dados["email"], FILTER_VALIDATE_EMAIL)){
            http_response_code(400);
            echo json_encode(["erro" => "E-mail inválido."]);
            die;
        }
        $email = filter_var($dados["email"], FILTER_VALIDATE_EMAIL);

        $cliente = new Cliente("", $email, $dados["senha"]);
        
        try {
            $autenticar = $this->servicoCliente->autenticarCliente($cliente);
            if($autenticar){
               $key = $_ENV["API_KEY"];
                $payload = [
                    "exp" => time() + 3600,
                    "iat" => time(),
                    "email" => $email
                ];

                $token = JWT::encode($payload, $key, "HS256");
                
                $_SESSION = [
                    "nome" => $autenticar["nome"],
                    "email" => $autenticar["email"],
                    "role" => $autenticar["role"],
                    "token" => $token
                ];

                http_response_code(200);
                echo json_encode(["token" => $token]);
                die;
            }
            http_response_code(401);
            echo json_encode(["erro" => "Credenciais inválidas."]);
            die;
        }catch(Throwable $e){
            http_response_code(500);
            echo json_encode(["erro" => "Erro ao gerar o token de acesso."]);
            die;
        }
    }
}