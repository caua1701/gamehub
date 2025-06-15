<?php
$root = $_SERVER['DOCUMENT_ROOT'];
include $root.'/view/template/header.php';
include $root.'/view/template/mensagem.php';
require_once $root.'/controller/AuthController.php';
$controller = new AuthController();

$usuarios = $controller->exibirUsuarios();
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perfil - MisterCaua</title>
    <link rel="stylesheet" href="/assets/css/admin.css">
</head>

<body>
<section>
    <div class="titulo-admin">
        <h1>Dashboard Administração</h1>
    </div>
</section>
<section>
    <div class="abas">
        <ul class="abas-options">
            <a href="/admin/dashboard">
                <li id="aba-atual">Gerenciar Usuários</li>
            </a>
            <a href="#"> 
                <li>Gerenciar Jogos</li>
            </a>
        </ul>
    </div>
    <div class="lista-usuarios">
        <ul class="usuarios">
            <?php if (empty($usuarios)): ?>
                <li>Nenhum usuário encontrado.</li>
            <?php else: ?>
                <?php foreach ($usuarios as $usuario):?>
                    <li>
                        <div class="usuario">
                            <a href="<?= '/perfil/'.$usuario['nome'];?>" target="__blank">Nome: <?= htmlspecialchars($usuario['nome']) ?></a>
                            <p>Email: <?=htmlspecialchars($usuario['email'])?></p>
                        </div>
                        <div class="acoes">
                            <a href="/admin/excluir-usuario?id=<?= htmlspecialchars($usuario['id']) ?>" 
                               class="delete-user-btn">
                               <img src="/assets/img/delete.svg" alt="Excluir">
                            </a>
                        </div>
                    </li>
                <?php endforeach; ?>
            <?php endif; ?>
        </ul>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
        const deleteButtons = document.querySelectorAll('.delete-user-btn');

        deleteButtons.forEach(button => {
            button.addEventListener('click', function(event) {
                // Previne o comportamento padrão do link (ir direto para o href)
                event.preventDefault();

                const userName = this.dataset.userName; // Pega o nome do usuário do atributo data-user-name
                const confirmMessage = `Tem certeza que deseja excluir o usuário "${userName}"? Esta ação é irreversível.`;

                // Exibe o popup de confirmação
                if (confirm(confirmMessage)) {
                    // Se o usuário clicar em "OK", redireciona para o link de exclusão
                    window.location.href = this.href;
                }
                });
            });
        });
    </script>
</section>
</body>

</html>