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
        <div class="editar-perfil">
            <h2>Editar Perfil</h2>
            <form action="/atualizar-perfil" method="POST">
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