<?php
require_once '../dao/UsuarioDAO.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $dao = new UsuarioDAO();

    if ($dao->existeUsuario($_POST['nome'])) {
        $mensagem = urlencode("Nome de usuário informado já está cadastrado! Tente outro nome de usuário ou realize o login!");
        header("Location: cadastro.php?mensagem=$mensagem");
        exit;
    }

    if ($dao->existeEmail($_POST['email'])) {
        $mensagem = urlencode("Email informado já está cadastrado! Tente outro email ou realize o login!");
        header("Location: cadastro.php?mensagem=$mensagem");
        exit;

    }
    
    $usuario = new Usuario();
    $usuario->setNomeUsuario($_POST['nome']);
    $usuario->setEmail($_POST['email']);
    $usuario->setSenha($_POST['senha']);

    $dao = new UsuarioDAO();
    $dao->cadastrar($usuario);

    header('Location: login.php');
    exit;
}
?>

<!-- <form method="post">
    Nome: <input type="text" name="nome" required><br>
    Email: <input type="email" name="email" required><br>
    Senha: <input type="password" name="senha" required><br>
    <button type="submit">Cadastrar</button>
</form> -->

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastrar</title>
    <link rel="stylesheet" href="../assets/css/cadastro.css">
</head>

<body>
    <main class="login">
        <article class="introducao">
            <span class="link-volta">Tem conta? <a href="login.php">Clique aqui</a></span>
            <img src="../assets/img/logo.png" alt="" width="300px">
            <h1>Conecte a milhares de jogadores ao redor do mundo no Gamehub.</h1>
        </article>
        <article class="form-login">
            <h2>Cadastrar</h2>
            <form method="POST">
                <div>
                    <label for="nome
                    ">Nome de Usuário:</label>
                    <input type="text" name="nome" id="nome" required placeholder="Sem caracteres especiais">
                </div>
                <div>
                    <label for="email">Email:</label>
                    <input type="email" name="email" id="email" required placeholder="exemplo@exemplo.com">
                </div>
                <div>
                    <label for="senha">Senha:</label>
                    <input type="password" name="senha" id="senha" required placeholder="Mínimo 8 caracteres">
                </div>
                <div>
                    <input id="botao-submit" type="submit" value="Criar conta">
                </div>
            </form>
        </article>
    </main>

    <script>
        const params = new URLSearchParams(window.location.search);
        if (params.has('mensagem')) {
            alert(decodeURIComponent(params.get('mensagem')));
        }
    </script>
</body>

</html>
