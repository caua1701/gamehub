<?php
class UsuarioDois {
    private $id;
    private $nomeUsuario;
    private $email;
    private $senha;

    // Getters e Setters
    public function getId() { return $this->id; }
    public function setId($id) { $this->id = $id; }

    public function getNomeUsuario() { return $this->nomeUsuario; }
    public function setNomeUsuario($nomeUsuario) { $this->nomeUsuario = $nomeUsuario; }

    public function getEmail() { return $this->email; }
    public function setEmail($email) { $this->email = $email; }

    public function getSenha() { return $this->senha; }
    public function setSenha($senha) { $this->senha = $senha; }
}
