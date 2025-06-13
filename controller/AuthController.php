<?php

require_once 'model/Usuario.php';
require_once 'utils/EmailHelper.php';

class AuthController {
    public function cadastrar() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $usuario = new Usuario();
            
            if ($usuario->existeUsuario($_POST['nome'])) {
                $mensagem = urlencode("Nome de usuário informado já está cadastrado! Tente outro nome de usuário ou realize o login!");
                header("Location: /gamehub/mensagem?msg=$mensagem");
                exit;
            }
    
            if ($usuario->existeEmail($_POST['email'])) {
                $mensagem = urlencode("Email informado já está cadastrado! Tente outro email ou realize o login!");
                header("Location: /gamehub/mensagem?msg=$mensagem");
                exit;
            }

            $usuario->setNomeUsuario($_POST["nome"]);
            $usuario->setEmail($_POST["email"]);
            $usuario->setSenha($_POST["senha"]);
            $usuario->cadastrar();
            header("Location: /gamehub/login");
            exit;
        }
    }

    public function login() {
        $usuario = new Usuario();
        $resultado = $usuario->autenticar($_POST['email'], $_POST['senha']);

        if ($resultado) {
            session_start();
            $_SESSION['logged'] = true;
            $_SESSION['user-id'] = $resultado['id'];
            $_SESSION['user-name'] = $resultado['nome'];
            header('Location: /gamehub/');
            exit;
        }
        else {
            $mensagem = urlencode("Email ou senha inválidos!");
            header("Location: /gamehub/mensagem?msg=$mensagem");
            exit;
        }
    }

    public function logout() {
        session_start();
        $_SESSION = [];
        session_destroy();
        header('Location: /gamehub/login');
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

            header('Location: /gamehub/mensagem?msg=Se o e-mail existir, você receberá o link');
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

    public function mostrarPerfil($nomeUsuario) {
        $usuario = new Usuario();
        $dados = $usuario->buscarPorNome($nomeUsuario);

        if ($dados) {
            // Envia os dados para a view
            require 'view/perfil.php';
        } else {
            http_response_code(404);
            echo "Usuário não encontrado!";
        }
    }

    public function editarPerfil($nomePerfil) {
        session_start();

        // Verifica se está logado
        if (!isset($_SESSION['user-id']) || !isset($_SESSION['user-name'])) {
            header('Location: /gamehub/login');
            exit;
        }

        // Confere se a slug da URL é igual à do usuário logado
        if ($_SESSION['user-name'] !== $nomePerfil) {
            header('Location: /gamehub/mensagem?msg=Acesso não autorizado.');
            exit;
        }

        $usuario = new Usuario();
        $dados = $usuario->buscarPorId($_SESSION['user-id']); // Seguro: via ID

        require 'view/editar_perfil.php';
    }

    public function salvarEdicao() {
        session_start();
     
        if (!isset($_SESSION['user-id'])) {
            header('Location: /gamehub/login');
            exit;
        }
     
        // Verifica se o ID enviado é o mesmo do usuário logado
        if ($_POST['id'] != $_SESSION['user-id']) {
            header('Location: /gamehub/mensagem?msg=Acesso não autorizado.');
            exit;
        }
     
        $idUsuario = $_SESSION['user-id']; // Use o ID da sessão para segurança
        $nomeNovo = trim($_POST['nome']); // Use trim para remover espaços em branco
        $emailNovo = trim($_POST['email']);
     
        // (Opcional) Validações básicas
        if (empty($nomeNovo) || empty($emailNovo)) {
            $mensagem = urlencode("Todos os campos são obrigatórios.");
            header('Location: /gamehub/mensagem?msg=' . $mensagem);
            exit;
        }

        $usuario = new Usuario();

        // 1. Obter o nome de usuário atual do banco de dados antes da atualização
        $dadosAtuais = $usuario->buscarPorId($idUsuario);
        $nomeUsuarioAtual = $dadosAtuais['nome'];

        // 2. Verificar se o novo nome de usuário é diferente do atual
        if ($nomeNovo !== $nomeUsuarioAtual) {
            // Se for diferente, verificar se o novo nome já existe
            if ($usuario->existeUsuario($nomeNovo)) {
                $mensagem = urlencode("Nome de usuário '$nomeNovo' já está em uso. Por favor, escolha outro.");
                header('Location: /gamehub/perfil/' . urlencode($nomeUsuarioAtual) . '/editar?msg=' . $mensagem); // Redireciona de volta para a edição com o nome antigo na URL e mensagem
                exit;
            }
        }
        
        // 3. Verificar se o novo email é diferente do atual
        // Se for diferente, verificar se o novo email já existe
        if ($emailNovo !== $dadosAtuais['email']) {
            if ($usuario->existeEmail($emailNovo)) {
                $mensagem = urlencode("Email '$emailNovo' já está em uso. Por favor, escolha outro.");
                header('Location: /gamehub/perfil/' . urlencode($nomeUsuarioAtual) . '/editar?msg=' . $mensagem);
                exit;
            }
        }

        // 4. Atualizar os dados no banco de dados
        $sucesso = $usuario->atualizarDados($idUsuario, $nomeNovo, $emailNovo);
     
        if ($sucesso) {
            // 5. Se a atualização foi bem-sucedida, atualizar a sessão
            $_SESSION['user-name'] = $nomeNovo;
            $mensagem = urlencode("Perfil atualizado com sucesso!");
            header('Location: /gamehub/perfil/' . urlencode($nomeNovo) . '/editar?sucesso=1&msg=' . $mensagem);
        } else {
            $mensagem = urlencode("Ocorreu um erro ao atualizar o perfil. Tente novamente.");
            header('Location: /gamehub/perfil/' . urlencode($nomeUsuarioAtual) . '/editar?erro=1&msg=' . $mensagem);
        }
        exit;
    }
}