<?php

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$method = $_SERVER['REQUEST_METHOD'];
$root = $_SERVER['DOCUMENT_ROOT'];

// Rota dinâmica para perfil de usuário
if (preg_match('#^/perfil/([^/]+)$#', $uri, $matches)) {
    $slug = $matches[1]; // Ex: "teste"
    require $root.'/controller/AuthController.php';
    $controller = new AuthController();
    $controller->mostrarPerfil($slug);
    exit;
}

// Editar perfil: /perfil/slug/editar
// if (preg_match('#^/perfil/([a-zA-Z0-9-_]+)/editar$#', $uri, $matches)) {
//     $slug = $matches[1];
    
// }

switch ($uri) {
    case '/':
        session_start();
        if (isset($_SESSION['user-name'])) {
            // Redireciona para o perfil do próprio usuário
            $nome = $_SESSION['user-name'];
            header("Location: /perfil/$nome");
        }
        else {
            header('Location: /login');
            
        }
        exit;

    case '/admin':
        session_start();
        // Verifica se a sessão está ativa e se o usuário é um administrador
        if (isset($_SESSION['logged']) && $_SESSION['logged'] && ($_SESSION['admin'])) {
            // Inclua a view do painel de administração
            require $root.'/view/admin.php';
        } else {
            http_response_code(403); // Status code 403 Forbidden (Acesso Proibido)
            echo "Acesso negado. Você não tem permissão para acessar esta página.";
        }
        exit; // Importante para parar a execução após lidar com a rota

    case '/cadastro':
        // Mostra a página de login
        require $root.'/view/cadastro.php';
        exit;

    case '/auth/cadastro':
        if ($method === 'POST') {
            require $root.'/controller/AuthController.php';
            $controller = new AuthController();
            $controller->cadastrar(); // processa o formulário
        } else {
            http_response_code(405);
            echo "Método não permitido";
        }
        exit;
    case '/login':
        // Mostra a página de login
        require $root.'/view/login.php';
        exit;
    case '/esqueceu-senha':
        // Mostra a página de login
        require $root.'/view/esqueceu_senha.php';
        exit;
    case '/auth/recuperar-conta':
        if ($method === 'POST') {
            require $root.'/controller/AuthController.php';
            $controller = new AuthController();
            $controller->recuperarConta(); // processa o formulário
        } else {
            http_response_code(405);
            echo "Método não permitido";
        }
        exit;
    case '/auth/redefinir-senha':
        if ($method === 'POST') {
            require $root.'/controller/AuthController.php';
            $controller = new AuthController();
            $controller->redefinirSenha(); // processa o formulário
        } else {
            http_response_code(405);
            echo "Método não permitido";
        }
        exit;

    case '/auth/login':
        if ($method === 'POST') {
            require $root.'/controller/AuthController.php';
            $controller = new AuthController();
            $controller->login(); // processa o formulário
        } else {
            http_response_code(405);
            echo "Método não permitido";
        }
        exit;
    case "/logout":
        require $root.'/controller/AuthController.php';
        $controller = new AuthController();
        $controller->logout(); // processa o formulário
        exit;
    case "/mensagem":
        require $root.'/view/mensagem.php';
        exit;

    case "/editar":
        require $root.'/controller/AuthController.php';
        $controller = new AuthController();
        $controller->editarPerfil();
        exit;


    case "/atualizar-perfil":
        if ($method === 'POST') {
            require $root.'/controller/AuthController.php';
            $controller = new AuthController();
            $controller->salvarEdicao();
        }
        exit;

    case '/admin/excluir-usuario': // NOVA ROTA PARA EXCLUSÃO
        if ($method === 'GET') { // Usamos GET para simplificar o link de exclusão com ID
            require $root.'/controller/AuthController.php';
            $controller = new AuthController();
            $controller->excluir(); // Chama o método de exclusão
        } else {
            http_response_code(405);
            echo "Método não permitido.";
        }
        exit;
    default:
        http_response_code(404);
        echo "404 - Página não encontrada";
        exit;
}
