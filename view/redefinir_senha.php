<?php
$token = $_GET['token'] ?? '';
?>

<!-- <form action="../controller/RedefinirSenhaController.php" method="post">
    <input type="hidden" name="token" value="<?= htmlspecialchars($token) ?>">
    <input type="password" name="nova_senha" placeholder="Nova senha" required>
    <button type="submit">Redefinir senha</button>
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
            <a href="/login"><img src="/assets/img/logo.png" alt="" width="300px"></a>
            <h1>Conecte a milhares de jogadores ao redor do mundo no Gamehub.</h1>
        </article>
        <article class="form-login">
            <h2>Redefinir Senha</h2>
            <form action="/auth/redefinir-senha" method="post">
                <input type="hidden" name="token" value="<?= htmlspecialchars($token) ?>">
                <div>
                    <label for="nova_senha">Senha nova:</label>
                    <input type="password" name="nova_senha" id="nova_senha" required>
                </div>
                <!-- <div>
                    <label for="">Digite novamente:</label>
                    <input type="password" name="" id="" required placeholder="Mínimo 8 caracteres">
                </div> -->
                <div>
                    <input id="botao-submit" type="submit" value="Redefinir Senha">
                </div>
            </form>
        </article>
    </main>
</body>

</html>