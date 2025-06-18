<?php
$root = $_SERVER['DOCUMENT_ROOT'];

require_once $root . '/model/Usuario.php';
require_once $root . '/utils/EmailHelper.php';

class AuthController
{
    public function validarCampos($nome, $email, $senha, $validarSenha)
    {
        // --- VALIDAÇÕES DE NOME ---
        // 1. Não vazio
        if (empty($nome)) {
            return "O nome de usuário não pode ser vazio.";
        }

        // 2. Caracteres permitidos (letras, números, underscore e hífen)
        if (!preg_match('/^[a-zA-Z0-9_-]+$/', $nome)) {
            return "O nome de usuário só pode conter letras, números, hífens e underscores.";
        }

        // --- VALIDAÇÕES DE EMAIL ---
        // 1. Não vazio
        if (empty($email)) {
            return "O e-mail não pode ser vazio.";
        }

        // 2. Verificar o formato do email.
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return "Formato de e-mail inválido.";
        }

        // --- VALIDAÇÕES DE SENHA ---
        // 1. Não vazio
        if ($validarSenha) {
            if (empty($senha)) {
                return "A senha não pode ser vazia.";
            }
            // 2. Mínimo de 8 caracteres
            if (strlen($senha) < 8) {
                return "A senha deve ter no mínimo 8 caracteres.";
            }
        }
    }
    
    public function cadastrar()
    {
        //Verificando se todos os campos foram preechidos, caso não, exibir mensagem pop-up
        if (!isset($_POST['nome']) || !isset($_POST['email']) || !isset($_POST['senha'])) {
            $mensagem = urlencode("Todos os campos são obrigatórios!");
            header("Location: /cadastro?sucesso=false&msg=$mensagem");
            exit;
        }

        //Salvando os campos nas variáveis
        $nome = $_POST['nome'];
        $email = $_POST['email'];
        $senha = $_POST['senha'];

        //Executando validação
        $resultado = $this->validarCampos($nome, $email, $senha, true);

        //Se retornar alguma mensagem, é porque deu algum erro
        if ($resultado) {
            $mensagem = urlencode($resultado);
            header("Location: /cadastro?sucesso=false&msg=$mensagem");
            exit;
        }

        //Instanciando o model Usuário
        $usuario = new Usuario();

        //Verificando se o nome  usuário já está cadastrado
        if ($usuario->existeUsuario($_POST['nome'])) {
            $mensagem = urlencode("Nome de usuário informado já está cadastrado! Tente outro nome de usuário ou realize o login!");
            header("Location: /mensagem?msg=$mensagem");
            exit;
        }

        //Verificando se o email  usuário já está cadastrado
        if ($usuario->existeEmail($_POST['email'])) {
            $mensagem = urlencode("Email informado já está cadastrado! Tente outro email ou realize o login!");
            header("Location: /mensagem?msg=$mensagem");
            exit;
        }

        //Se tudo ocorrer certo, irá salvar os campos nas variáveis do model
        $usuario->setNomeUsuario($nome);
        $usuario->setEmail($email);
        $usuario->setSenha($senha);

        //Executa 
        $usuario->cadastrar();
        header("Location: /login");
        exit;

    }

    public function login()
    {
        $usuario = new Usuario();
        $resultado = $usuario->autenticar($_POST['email'], $_POST['senha']);

        if ($resultado) {
            session_start();
            $_SESSION['logged'] = true;
            $_SESSION['user-id'] = $resultado['id'];
            $_SESSION['user-name'] = $resultado['nome'];

            if ($resultado['id'] === 1) {
                $_SESSION['admin'] = true;
            }
            header('Location: /');
            exit;
        } else {
            $mensagem = urlencode("Email ou senha inválidos!");
            header("Location: /mensagem?msg=$mensagem");
            exit;
        }
    }

    public function logout()
    {
        session_start();
        $_SESSION = [];
        session_destroy();
        header('Location: /login');
        exit;
    }


    public function recuperarConta()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $usuario = new Usuario();
            $email = $_POST['email'];
            $resultado = $usuario->existeEmail($email);

            if ($resultado) {
                $token = bin2hex(random_bytes(32));
                $expirar = date('Y-m-d H:i:s', strtotime('+1 hour'));
                $usuario->salvarToken($email, $token, $expirar);

                $link = "http://localhost/view/redefinir_senha.php?token=$token";
                $mensagem = "
                <h2>Recuperação de senha</h2>
                <p>Olá, clique no link abaixo para redefinir sua senha (válido por 1 hora):</p>
                <p><a href='$link'>Redefinir senha</a></p>
                ";

                EmailHelper::enviar($email, "Redefinir Senha!", $mensagem);
            }

            header('Location: /mensagem?msg=Se o e-mail existir, você receberá o link');
        }
    }

    public function redefinirSenha()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $token = $_POST['token'];
            $senhaNova = $_POST['nova_senha'];

            // --- VALIDAÇÕES DE SENHA ---
            // 1. Não vazio
            if (empty($senhaNova)) {
                $mensagem = urlencode("A senha nova não pode ser vazia.");
                header("Location: /redefinir-senha?sucesso=false&msg=$mensagem");
                exit;
            }
            // 2. Mínimo de 8 caracteres
            if (strlen($senhaNova) < 8) {
                $mensagem = urlencode("A senha nova deve ter no mínimo 8 caracteres.");
                header("Location: /redefinir-senha?sucesso=false&msg=$mensagem");
                exit;
            }
            
            $usuario = new Usuario();
            $resultado = $usuario->verificarToken($token);

            if ($resultado) {
                $novaSenhaHash = password_hash($senhaNova, PASSWORD_DEFAULT);
                $usuario->atualizarSenha($resultado['id'], $novaSenhaHash);

                header('Location: /');
                exit;
            } else {
                header('Location: /mensagem?msg=Token Inválido ou expirado.');
            }
        }
    }

    public function mostrarPerfil($nomeUsuario)
    {
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

    public function editarPerfil()
    {
        session_start();

        // Verifica se está logado
        if (!isset($_SESSION['logged'])) {
            header('Location: /login');
            exit;
        }

        $usuario = new Usuario();
        $dados = $usuario->buscarPorId($_SESSION['user-id']); // Seguro: via ID

        require 'view/editar_perfil.php';
        exit;
    }

    public function salvarEdicao()
    {
        session_start();

        if (!isset($_SESSION['logged'])) {
            header('Location: /login');
            exit;
        }

        // Verifica se o ID enviado é o mesmo do usuário logado
        if ($_POST['id'] != $_SESSION['user-id']) {
            header('Location: /mensagem?msg=Acesso não autorizado.');
            exit;
        }

        $idUsuario = $_SESSION['user-id']; // Use o ID da sessão para segurança
        $nomeNovo = $_POST['nome']; // Use trim para remover espaços em branco
        $emailNovo = $_POST['email'];

        // (Opcional) Validações básicas
        if (empty($nomeNovo) || empty($emailNovo)) {
            $mensagem = urlencode("Todos os campos são obrigatórios.");
            header('Location: /mensagem?msg=' . $mensagem);
            exit;
        }

        $resultado = $this->validarCampos($nomeNovo, $emailNovo, null, false);

        if ($resultado) {
            $mensagem = urlencode($resultado);
            header("Location: /editar?sucesso=false&msg=$mensagem");
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
                header('Location: /editar?msg=' . $mensagem); // Redireciona de volta para a edição com o nome antigo na URL e mensagem
                exit;
            }
        }

        // 3. Verificar se o novo email é diferente do atual
        // Se for diferente, verificar se o novo email já existe
        if ($emailNovo !== $dadosAtuais['email']) {
            if ($usuario->existeEmail($emailNovo)) {
                $mensagem = urlencode("Email '$emailNovo' já está em uso. Por favor, escolha outro.");
                header('Location: /editar?msg=' . $mensagem);
                exit;
            }
        }

        // 4. Atualizar os dados no banco de dados
        $sucesso = $usuario->atualizarDados($idUsuario, $nomeNovo, $emailNovo);

        if ($sucesso) {
            // 5. Se a atualização foi bem-sucedida, atualizar a sessão
            $_SESSION['user-name'] = $nomeNovo;
            $mensagem = urlencode("Perfil atualizado com sucesso!");
            header('Location: /editar?sucesso=true&msg=' . $mensagem);
        } else {
            $mensagem = urlencode("Ocorreu um erro ao atualizar o perfil. Tente novamente.");
            header('Location: /editar?erro=1&msg=' . $mensagem);
        }
        exit;
    }

    public function excluir()
    {
        session_start();

        // 1. Verificar se o usuário está logado e é um administrador
        if (!isset($_SESSION['logged']) || !($_SESSION['admin'])) {
            $mensagem = urlencode("Acesso negado. Você não tem permissão para realizar esta ação.");
            header("Location: /mensagem?msg=$mensagem");
            exit;
        }

        // 2. Obter o ID do usuário a ser excluído da URL (GET) ou formulário (POST)
        // Usaremos GET para simplificar o link do delete, mas POST seria mais robusto
        $userIdToDelete = $_GET['id'] ?? null;

        if (!$userIdToDelete || !is_numeric($userIdToDelete)) {
            $mensagem = urlencode("ID do usuário inválido para exclusão.");
            header("Location: /admin/dashboard?status=error&msg=$mensagem"); // Redireciona de volta ao dashboard
            exit;
        }

        // 3. Opcional: Impedir que o admin se exclua
        if ($userIdToDelete == $_SESSION['user-id']) {
            $mensagem = urlencode("Você não pode excluir sua própria conta através do painel de administração.");
            header("Location: /admin?sucesso=false&msg=$mensagem");
            exit;
        }

        // 4. Chamar o método de exclusão do modelo
        $usuario = new Usuario();
        if ($usuario->excluirUsuario($userIdToDelete)) {
            $mensagem = urlencode("Usuário excluído com sucesso!");
            header("Location: /admin?sucesso=true&msg=$mensagem");
            exit;
        } else {
            $mensagem = urlencode("Erro ao excluir usuário. Tente novamente.");
            header("Location: /admin?sucesso=false&msg=$mensagem");
            exit;
        }
    }

    public function exibirUsuarios()
    {
        //Instanciando a classe e executando o método
        $usuario = new Usuario();
        $listaUsuarios = $usuario->listarTodosUsuarios();
        //Retorna o resultado para a página admin.php
        return $listaUsuarios;
    }
}