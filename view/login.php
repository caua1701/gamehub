<?php
session_start();

// Redireciona se já estiver logado
if (isset($_SESSION['usuario'])) {
    header('Location: perfil.php');
    exit;
}

require_once '../dao/UsuarioDAO.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $dao = new UsuarioDAO();
    $usuario = $dao->autenticar($_POST['email'], $_POST['senha']);

    if ($usuario) {
        $_SESSION['usuario'] = $usuario;
        header('Location: perfil.php');
        exit;
    } else {
        $mensagem = urlencode("Email ou senha inválidos!");
        header("Location: login.php?mensagem=$mensagem");
        exit;

    }
}
?>

<!-- <form method="post">
    Email: <input type="email" name="email" required><br>
    Senha: <input type="password" name="senha" required><br>
    <button type="submit">Login</button>
</form> -->

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Entrar na conta</title>
    <link rel="stylesheet" href="../assets/css/cadastro.css">
</head>

<body>
    <main class="login">

        <article class="introducao">
            <span class="link-volta">Não possui conta? <a href="cadastro.php">Clique aqui</a></span>
            <img src="../assets/img/logo.png" alt="" width="300px">
            <h1>Conecte a milhares de jogadores ao redor do mundo no Gamehub.</h1>
        </article>
        <article class="form-login">
            <h2>Login</h2>
            <form method="post">
                <div>
                    <label for="email">Email:</label>
                    <input type="email" name="email" id="email" required>
                </div>
                <div>
                    <label for="senha">Senha:</label>
                    <input type="password" name="senha" id="senha" required>
                </div>
                <div>
                    <input id="botao-submit" type="submit" value="Entrar na conta">
                </div>
            </form>
            <div>
                <h3><a href="esqueceu_senha.php">Esqueceu a senha?</a></h3>
            </div>
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
