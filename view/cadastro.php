<?php
$root = $_SERVER['DOCUMENT_ROOT'];
include $root.'/view/template/mensagem.php';

session_start();

if (isset($_SESSION['logged']) && $_SESSION['logged']) {
    header('Location: /');
}
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastrar</title>
    <link rel="stylesheet" href="/assets/css/cadastro.css">
</head>

<body>
    <main class="login">
        <article class="introducao">
            <span class="link-volta">Tem conta? <a href="login.php">Clique aqui</a></span>
            <img src="/assets/img/logo.png" alt="" width="300px">
            <h1>Conecte a milhares de jogadores ao redor do mundo no Gamehub.</h1>
        </article>
        <article class="form-login">
            <h2>Cadastrar</h2>
            <form method="POST" action="/auth/cadastro">
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
