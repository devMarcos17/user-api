<?php

namespace Marcos\Servico;

use PDO;
use Marcos\Modelo\Cliente;
class ServicoCliente
{
    public function __construct(
        private PDO $conexao
    )
    {
        $this->conexao = $conexao;
    }

    public function cadastrarCliente(Cliente $cliente): bool
    {
        $hash = password_hash($cliente->getSenha(), PASSWORD_DEFAULT);

        $query = "INSERT INTO clientes (nome, email, senha, role) VALUES (?, ?, ?, ?)";
        
        $stmt = $this->conexao->prepare($query);

        $stmt->bindValue(1, $cliente->getNome());
        $stmt->bindValue(2, $cliente->getEmail());
        $stmt->bindValue(3, $hash);
        $stmt->bindValue(4, $cliente->getRole());

        $sucesso = $stmt->execute();

        if($sucesso){
            return true;
        }
        return false;
    }
    public function atualizarCliente(Cliente $cliente): bool
    {
        $hash = password_hash($cliente->getSenha(), PASSWORD_DEFAULT);

        $query = "UPDATE clientes SET nome = ?, email = ?, senha = ? WHERE id = ?";
        
        $stmt = $this->conexao->prepare($query);

        $stmt->bindValue(1, $cliente->getNome());
        $stmt->bindValue(2, $cliente->getEmail());
        $stmt->bindValue(3, $hash);
        $stmt->bindValue(4, $cliente->getId(), PDO::PARAM_INT);

        $sucesso = $stmt->execute();

        if($sucesso){
            return true;
        }
        return false;
    }
    public function deletarCliente(Cliente $cliente): bool
    {
        $query = "DELETE FROM clientes WHERE id = ?";
        
        $stmt = $this->conexao->prepare($query);

        $stmt->bindValue(1, $cliente->getId(), PDO::PARAM_INT);

        $sucesso = $stmt->execute();

        if($sucesso){
            return true;
        }
        return false;
    }
    public function listarClientes(): array
    {
        $query = "SELECT *FROM clientes";
        $stmt = $this->conexao->query($query);

        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function listarCliente(Cliente $cliente): array|null
    {
        $query = "SELECT *FROM clientes WHERE id = ?";
        
        $stmt = $this->conexao->prepare($query);

        $stmt->bindValue(1, $cliente->getId(), PDO::PARAM_INT);

        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;

    }
    public function autenticarCliente(Cliente $cliente): array|null
    {
        $query = "SELECT *FROM clientes WHERE email = ?";
        
        $stmt = $this->conexao->prepare($query);

        $stmt->bindValue(1, $cliente->getEmail());

        $stmt->execute();

        $clienteBanco = $stmt->fetch(PDO::FETCH_ASSOC);

        if($clienteBanco && password_verify($cliente->getSenha(), $clienteBanco["senha"])){
            return $clienteBanco;
        }
        return null;


    }
}