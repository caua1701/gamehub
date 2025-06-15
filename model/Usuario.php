<?php
require_once 'conexao/Conexao.php';
class Usuario {
    private $conn;

    private $id;
    private $nomeUsuario;
    private $email;
    private $senha;
    private $token_recuperacao;
    private $token_expiracao;

    public function __construct() {
        $conexao = new Conexao();
        $this->conn = $conexao->getConexao();
    }
    // Getters e Setters
    public function getId() { return $this->id; }

    public function getNomeUsuario() { return $this->nomeUsuario; }
    public function setNomeUsuario($nomeUsuario) { $this->nomeUsuario = $nomeUsuario; }

    public function getEmail() { return $this->email; }
    public function setEmail($email) { $this->email = $email; }

    public function getSenha() { return $this->senha; }
    public function setSenha($senha) { $this->senha = $senha; }

    public function cadastrar() {
        $sql = "INSERT INTO usuario (nome, email, senha) VALUES (?, ?, ?)";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([
            $this->getNomeUsuario(),
            $this->getEmail(),
            password_hash($this->getSenha(), PASSWORD_DEFAULT)
        ]);
    }

    public function autenticar($email, $senha) {
        $sql = "SELECT * FROM usuario WHERE email = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$email]);

        if ($stmt->rowCount() === 1) {
            $usuario = $stmt->fetch(PDO::FETCH_ASSOC);
            if (password_verify($senha, $usuario['senha'])) {
                return $usuario;
            }
        }

        return false;
    }

    public function existeUsuario($nome) {
        $sql = "SELECT id FROM usuario WHERE nome = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$nome]);
        return $stmt->rowCount() > 0;
    }

    public function existeEmail($email) {
        $sql = "SELECT id FROM usuario WHERE email = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$email]);
        return $stmt->rowCount() > 0;
    }

    public function buscarPorEmail($email) {
    $sql = "SELECT * FROM usuario WHERE email = :email";
    $stmt = $this->conn->prepare($sql);
    $stmt->bindValue(":email", $email);
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function salvarToken($email, $token, $expira_em) {
        $sql = "UPDATE usuario SET token_recuperacao = :token, token_expiracao = :expira_em WHERE email = :email";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':token', $token);
        $stmt->bindParam(':expira_em', $expira_em);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
    }

    public function verificarToken($token) {
        $sql = "SELECT * FROM usuario WHERE token_recuperacao = :token AND token_expiracao > NOW()";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':token', $token);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function atualizarSenha($id_usuario, $novaSenhaHash) {
        $sql = "UPDATE usuario SET senha = :senha, token_recuperacao = NULL, token_expiracao = NULL WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':senha', $novaSenhaHash);
        $stmt->bindValue(':id', $id_usuario);
        $stmt->execute();
    }

    public function buscarPorId($id) {
        $stmt = $this->conn->prepare("SELECT * FROM usuario WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function buscarPorNome($nome) {
        $sql = "SELECT * FROM usuario WHERE nome = :nome";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':nome', $nome);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function atualizarDados($id, $nome, $email) {
        $stmt = $this->conn->prepare("UPDATE usuario SET nome = ?, email = ? WHERE id = ?");
        return $stmt->execute([$nome, $email, $id]);
    }

    public function excluirUsuario($id) {
        $sql = "DELETE FROM usuario WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([$id]); // Retorna true em caso de sucesso, false em caso de falha
    }

    public function listarTodosUsuarios() {
        $sql = "SELECT id, nome, email FROM usuario ORDER BY nome ASC"; // Selecione apenas os campos necessários
        $stmt = $this->conn->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC); // Retorna todos os usuários
    }
}
