<?php
require_once 'verifica_login.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perfil - MisterCaua</title>
    <link rel="stylesheet" href="../assets/css/perfil.css">
</head>

<body>
    <header>
        <nav>
            <img src="img/logo.png" alt="" width="60px">
            <form action="">
                <input type="text" name="" id="" placeholder="Procurar jogos">
            </form>
            <div class="foto-perfil-header">
                C
            </div>
        </nav>
    </header>
    <section>
        <div class="perfil">
            <img class="img-banner" src="../assets/img/banner.png" alt="Banner do perfil">
            <div class="user-perfil">
                <div class="foto-perfil">
                    C
                </div>
                <h1>MisterCaua</h1>
                <a href="logout.php">Sair</a>
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
                <a href="perfil-amigos.html">
                    <li>Amigos</li>
                </a>
                <a href="perfil-amizade-pendente.html">
                    <li>Amizades Pendentes</li>
                </a>
            </ul>
        </div>
        <div class="lista-jogos">
            <img src="../assets/img/jogo2.png" alt="Jogo 2">
            <img src="../assets/img/jogo1.png" alt="Jogo 1">
            <img src="../assets/img/jogo3.png" alt="Jogo 3">
            <img src="../assets/img/jogo4.png" alt="Jogo 4">
            <img src="../assets/img/jogo5.png" alt="Jogo 5">
            <img src="../assets/img/jogo6.png" alt="Jogo 6">
            <img src="../assets/img/jogo7.png" alt="Jogo 7">
            <img src="../assets/img/jogo8.png" alt="Jogo 8">
            <img src="../assets/img/jogo9.png" alt="Jogo 9">
            <img src="../assets/img/jogo10.png" alt="Jogo 10">
        </div>
    </section>
</body>

</html>