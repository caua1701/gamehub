<?php

require_once '../model/Usuario.php';
require_once '../utils/EmailHelper.php';

class AuthController {
    public function cadastrar() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $usuario = new Usuario();
            
            if ($usuario->existeUsuario($_POST['nome'])) {
                $mensagem = urlencode("Nome de usuário informado já está cadastrado! Tente outro nome de usuário ou realize o login!");
                header("Location: cadastro.php?mensagem=$mensagem");
                exit;
            }
    
            if ($usuario->existeEmail($_POST['email'])) {
                $mensagem = urlencode("Email informado já está cadastrado! Tente outro email ou realize o login!");
                header("Location: cadastro.php?mensagem=$mensagem");
                exit;
            }

            $usuario->setNomeUsuario($_POST["nome"]);
            $usuario->setEmail($_POST["email"]);
            $usuario->setSenha($_POST["senha"]);
            $usuario->cadastrar();
        }
    }

    public function login() {
        $usuario = new Usuario();
        $resultado = $usuario->autenticar($_POST['email'], $_POST['senha']);

        if ($resultado) {
            session_start();
            $_SESSION['user'] = $resultado['nome'];
            header('Location: ../view/perfil.php');
            exit;
        }
        else {
            $mensagem = urlencode("Email ou senha inválidos!");
            header("Location: ../view/login.php?mensagem=$mensagem");
            exit;
        }
    }

    public function logout() {
        session_start();
        $_SESSION = [];
        session_destroy();
        header('Location: /login');
        exit;
    }
    

    public function recuperarConta() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $usuario = new Usuario();
            $email = $_POST['email'];
            $resultado = $usuario->existeEmail($email);

            if ($resultado) {
                $token = bin2hex(random_bytes(32));
                $expirar = date('Y-m-d H:i:s', strtotime('+1 hour'));
                $usuario->salvarToken($email, $token, $expirar);

                $link = "http://localhost/gamehub/view/redefinir_senha.php?token=$token";
                $mensagem = "
                <h2>Recuperação de senha</h2>
                <p>Olá, clique no link abaixo para redefinir sua senha (válido por 1 hora):</p>
                <p><a href='$link'>Redefinir senha</a></p>
                ";

                EmailHelper::enviar($email, "Redefinir Senha!", $mensagem);
            }

            header('Location: ../view/mensagem.php?msg=Se o e-mail existir, você receberá o link');
        }
    }

    public function redefinirSenha() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $token = $_POST['token'];
            $senhaNova = $_POST['nova_senha'];
            $usuario = new Usuario();

            $resultado = $usuario->verificarToken( $token);

            if ($resultado) {
                $novaSenhaHash = password_hash($senhaNova, PASSWORD_DEFAULT);
                $usuario->atualizarSenha( $resultado['id'], $novaSenhaHash );
            }
        }
    }
}