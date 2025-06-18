<style>
    @import url("global.css");
    header {
        width: 100%;
        background-color: var(--cor-secundaria);
        padding: 5px;
        position: relative;
    }

    nav {
        max-width: 1200px;
        display: grid;
        grid-template-columns: auto 1fr auto;
        gap: 50px;
        align-items: center;
        margin: auto;
    }

    .foto-perfil-header {
        width: 50px;
        height: 50px;
        border-radius: 100px;
        background-color: aqua;
        text-align: center;
        display: flex;
        justify-content: center;
        align-items: center;
        font-size: 1.475rem;
    }

    .busca input {
        width: 100%;
        max-height: 50px;
        padding: 20px 30px;
        font-size: 1.25rem;
        background-color: var(--cinza-input);
        border: none;
        border-radius: 10px;
        outline: 0;
    }

    .btn-admin div{
        padding: 15px;
        font-size: 1.125rem;
        background-color: var(--cor-fundo);
        position: absolute;
        left: 0;
        top: 0;
        width: 100px;
        height: 100%;
    }

    .bnt-admin div:hover {
        background-color: var(--cor-secundaria);
        cursor: pointer;
    }

</style>

<header>
    <nav>
        <img src="/assets/img/logo.png" alt="" width="50px">
        <form class="busca" action="">
            <input type="text" name="" id="" placeholder="Procurar jogos">
        </form>
        <div class="foto-perfil-header">
            C
        </div>
    </nav>
    <?php
    if (isset($_SESSION['admin'])):?>
        <a class="btn-admin" href="/admin"><div>Admin</div></a>
    <?php endif ?>
</header>