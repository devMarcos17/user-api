<?php

namespace Marcos\Modelo;

class Cliente
{
    public function __construct(
        private string $nome,
        private string $email,
        private string $senha,
        private string $role = "user",
        private ?int $id = null
    )
    {
        
    }
    public function getNome(): string
    {
        return $this->nome;
    }
    public function getEmail(): string
    {
        return $this->email;
    }
    public function getSenha(): string
    {
        return $this->senha;
    }
    public function getRole(): string
    {
        return $this->role;
    }
    public function getId(): int
    {
        return $this->id;
    }
}