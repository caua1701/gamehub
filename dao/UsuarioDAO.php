<?php
require_once __DIR__ . '/../conexao/Conexao.php';
require_once __DIR__ . '/../model/Usuario.php';

class UsuarioDAO {
    private $conn;

    public function __construct() {
        $this->conn = Conexao::getConexao();
    }

    public function cadastrar(Usuario $usuario) {
        $sql = "INSERT INTO usuario (nome, email, senha) VALUES (?, ?, ?)";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([
            $usuario->getNomeUsuario(),
            $usuario->getEmail(),
            password_hash($usuario->getSenha(), PASSWORD_DEFAULT)
        ]);
    }

    public function autenticar($email, $senha) {
        $sql = "SELECT * FROM usuario WHERE email = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$email]);

        if ($stmt->rowCount() > 0) {
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

    public function salvarTokenRedefinicao($email, $token, $expira_em) {
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
}
