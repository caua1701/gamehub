<?php

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$method = $_SERVER['REQUEST_METHOD'];

// Rota dinâmica para perfil de usuário
if (preg_match('#^/gamehub/perfil/([^/]+)$#', $uri, $matches)) {
    $slug = $matches[1]; // Ex: "teste"
    require 'controller/AuthController.php';
    $controller = new AuthController();
    $controller->mostrarPerfil($slug);
    exit;
}

// Editar perfil: /gamehub/perfil/slug/editar
if (preg_match('#^/gamehub/perfil/([a-zA-Z0-9-_]+)/editar$#', $uri, $matches)) {
    $slug = $matches[1];
    require 'controller/AuthController.php';
    $controller = new AuthController();
    $controller->editarPerfil($slug);
    exit;
}

switch ($uri) {
    case '/gamehub/':
        session_start();

        if (isset($_SESSION['user-name'])) {
            // Redireciona para o perfil do próprio usuário
            $nome = $_SESSION['user-name'];
            header("Location: /gamehub/perfil/$nome");
            exit;
        }
        break;
    case '/gamehub/cadastro':
        // Mostra a página de login
        require 'view/cadastro.php';
        break;

    case '/gamehub/auth/cadastro':
        if ($method === 'POST') {
            require 'controller/AuthController.php';
            $controller = new AuthController();
            $controller->cadastrar(); // processa o formulário
        } else {
            http_response_code(405);
            echo "Método não permitido";
        }
        break;
    case '/gamehub/login':
        // Mostra a página de login
        require 'view/login.php';
        break;

    case '/gamehub/auth/login':
        if ($method === 'POST') {
            require 'controller/AuthController.php';
            $controller = new AuthController();
            $controller->login(); // processa o formulário
        } else {
            http_response_code(405);
            echo "Método não permitido";
        }
        break;
    case "/gamehub/logout":
        require 'controller/AuthController.php';
        $controller = new AuthController();
        $controller->logout(); // processa o formulário
        break;
    case "/gamehub/mensagem":
        require 'view/mensagem.php';
        break;

    case "/gamehub/atualizar-perfil":
        if ($method === 'POST') {
            require 'controller/AuthController.php';
        $controller = new AuthController();
        $controller->salvarEdicao();
        exit;
        }
        break;
    default:
        http_response_code(404);
        echo "404 - Página não encontrada";
        break;
}
