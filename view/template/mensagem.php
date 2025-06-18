<?php // view/template/message_popup.php ?>


<div id="messageContainer" class="message-container">
    <span id="messageText"></span>
    <button class="message-close-btn">&times;</button>
</div>


<style>
    /* Este CSS pode ser movido para assets/css/perfil.css (ou um CSS dedicado) */
    .message-container {
        position: fixed; /* Fixa a mensagem na tela */
        display: none;
        top: 20px; /* Distância do topo */
        right: 20px; /* Distância da direita */
        z-index: 1000; /* Garante que fique acima de outros elementos */
        padding: 15px 25px;
        border-radius: 5px;
        font-family: Arial, sans-serif;
        color: white;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        opacity: 0; /* Começa invisível */
        transform: translateY(-20px); /* Começa um pouco acima para animação */
        transition: opacity 0.3s ease-out, transform 0.3s ease-out;
        display: flex; /* Para alinhar o texto e o botão de fechar */
        align-items: center;
        justify-content: space-between;
        min-width: 250px;
    }

    .message-container.show {
        opacity: 1;
        transform: translateY(0);
    }

    .message-container.success {
        background-color: #4CAF50; /* Verde */
    }

    .message-container.error {
        background-color: #f44336; /* Vermelho */
    }

    .message-close-btn {
        background: none;
        border: none;
        color: white;
        font-size: 20px;
        cursor: pointer;
        margin-left: 15px;
        line-height: 1;
    }
    .message-close-btn:hover {
        opacity: 0.8;
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
    const urlParams = new URLSearchParams(window.location.search);
    const sucesso = urlParams.get('sucesso');
    const message = urlParams.get('msg');

    const messageContainer = document.getElementById('messageContainer');
    const messageText = document.getElementById('messageText');
    const messageCloseBtn = document.querySelector('.message-close-btn');

    if (sucesso && message) {
        const decodedMessage = decodeURIComponent(message);
        messageText.textContent = decodedMessage; // Define o texto da mensagem

        // Adiciona a classe de status para a cor de fundo
        if (sucesso === 'true') {
            messageContainer.classList.add('success');
        } else {
            messageContainer.classList.add('error');
        }

        // Exibe a mensagem
        messageContainer.classList.add('show');

        // Oculta a mensagem automaticamente após 5 segundos (opcional)
        setTimeout(() => {
            messageContainer.classList.remove('show');
            // Opcional: Remover as classes de status após a animação para reuso
            setTimeout(() => {
                messageContainer.classList.remove('success', 'error');
            }, 300); // Deve ser igual ou maior que o tempo de transição CSS
        }, 5000); // 5 segundos

        // Adiciona evento para fechar a mensagem manualmente
        messageCloseBtn.addEventListener('click', () => {
            messageContainer.classList.remove('show');
            setTimeout(() => {
                messageContainer.classList.remove('success', 'error');
            }, 300);
        });

        // Opcional: Remover os parâmetros da URL para que a mensagem não apareça novamente
        // se o usuário atualizar a página
        if (history.replaceState) {
            const cleanUrl = window.location.protocol + "//" + window.location.host + window.location.pathname;
            history.replaceState({path: cleanUrl}, '', cleanUrl);
        }
    }
});
</script>