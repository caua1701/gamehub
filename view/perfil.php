<?php
$root = $_SERVER['DOCUMENT_ROOT'];
include $root.'/view/template/header.php';

if ( session_status() !== PHP_SESSION_ACTIVE )
 {
    session_start();
}

if (isset($_SESSION['logged']) && $_SESSION['logged']) {
    $user_id = $_SESSION['user-id'];
    $user_name = $_SESSION['user-name'];
} else {
    header('Location: /login');
    exit;
}
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perfil - <?php echo htmlspecialchars($dados['nome'])?></title>
    <link rel="stylesheet" href="/assets/css/perfil.css">
</head>

<body>
    <section>
        <div class="perfil">
        <?php
            if (isset($dados['id']) && $dados['id'] == $_SESSION['user-id']) {
            ?>
                <a href="/editar">
                    <img class="img-edit" src="/assets/img/settings.svg" alt="Editar Perfil" width="30px">
                </a>
            <?php
            }
            ?>
            
            <!-- <img class="img-banner" src="/assets/img/banner.png" alt="Banner do perfil"> -->
            <div class="user-perfil">
                <div class="foto-perfil">
                    C
                </div>
                <h1><?php echo htmlspecialchars($dados['nome'])?></h1>
                <?php
                if (isset($dados['id']) && $dados['id'] == $_SESSION['user-id']) {
                ?>
                    <a href="/logout">Sair</a>
                <?php
                }
                ?>
                
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
                <a href="perfil-jogos.html">
                    <li id="aba-atual">Jogos</li>
                </a>
            </ul>
        </div>
        <div class="lista-jogos">
            <img src="/assets/img/jogo2.png" alt="Jogo 2">
            <img src="/assets/img/jogo1.png" alt="Jogo 1">
            <img src="/assets/img/jogo3.png" alt="Jogo 3">
            <img src="/assets/img/jogo4.png" alt="Jogo 4">
            <img src="/assets/img/jogo5.png" alt="Jogo 5">
            <img src="/assets/img/jogo6.png" alt="Jogo 6">
            <img src="/assets/img/jogo7.png" alt="Jogo 7">
            <img src="/assets/img/jogo8.png" alt="Jogo 8">
            <img src="/assets/img/jogo9.png" alt="Jogo 9">
            <img src="/assets/img/jogo10.png" alt="Jogo 10">
        </div>
    </section>
</body>

</html>