<style>
    header {
        width: 100%;
        background-color: var(--cor-secundaria);
        padding: 5px;
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
</style>

<header>
    <nav>
        <img src="../assets/img/logo.png" alt="" width="60px">
        <form class="busca" action="">
            <input type="text" name="" id="" placeholder="Procurar jogos">
        </form>
        <div class="foto-perfil-header">
            C
        </div>
    </nav>
</header>