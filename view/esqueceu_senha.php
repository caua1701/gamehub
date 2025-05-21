<!-- <form action="../controller/RecuperarSenhaController.php" method="post">
    <input type="email" name="email" placeholder="Digite seu e-mail" required>
    <button type="submit">Recuperar senha</button>
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
            <!-- <span class="link-volta"><a href="cadastro.html">Página de Login</a></span> -->
            <a href="../view/login.php"><img src="../assets/img/logo.png" alt="" width="300px"></a>
            <h1>Conecte a milhares de jogadores ao redor do mundo no Gamehub.</h1>
        </article>
        <article class="form-login">
            <h2>Recuperar conta</h2>
            <form action="../controller/RecuperarSenhaController.php" method="post">
                <div>
                    <label for="">Digite o email da sua conta:</label>
                    <input type="email" name="email" id="email" placeholder="Digite seu e-mail" required>
                </div>
                <div>
                    <input id="botao-submit" type="submit" value="Recuperar Senha">
                </div>
            </form>
        </article>
    </main>
</body>

</html>
