<?php
include 'view/template/header.php';
include 'view/template/mensagem.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perfil - MisterCaua</title>
    <link rel="stylesheet" href="../../assets/css/perfil.css">
</head>

<body>
    <section>
        <div class="perfil">
            <img class="img-banner" src="../../assets/img/banner.png" alt="Banner do perfil">
            <div class="user-perfil">
                <div class="foto-perfil">
                    C
                </div>
                <h1><?php echo htmlspecialchars($dados['nome'])?></h1>
            </div>
        </div>
        <ul class="status-jogos">
            <li>5 Planejando</li>
            <li>5 Zerados</li>
            <li>15 Jogando</li>
        </ul>
    </section>
    <section>
        <div class="abas">
            <ul class="abas-options">
                <a href="/gamehub/">
                    <li>Jogos</li>
                </a>
                <a href="">
                    <li id="aba-atual">Editar</li>
                </a>
            </ul>
        </div>
        <div class="editar-perfil">
            <h2>Editar Perfil</h2>
            <form action="/gamehub/atualizar-perfil" method="POST">
                <input type="hidden" name="id" value="<?= htmlspecialchars($dados['id']) ?>">
                <label>Nome: 
                <input type="text" name="nome" value="<?= htmlspecialchars($dados['nome']) ?>">
                </label><br>
                <label>Email:
                <input type="email" name="email" value="<?= htmlspecialchars($dados['email']) ?>">
                </label><br>
                <button type="submit">Salvar alterações</button>
            </form>
        </div>
    </section>
</body>

</html>