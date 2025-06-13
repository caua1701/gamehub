<?php
require_once '../dao/UsuarioDAO.php';
require_once '../utils/EmailHelper.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $usuarioDAO = new UsuarioDAO();
    $usuario = $usuarioDAO->buscarPorEmail($email);

    if ($usuario) {
        $token = bin2hex(random_bytes(32));
        $expira_em = date('Y-m-d H:i:s', strtotime('+1 hour'));
        $usuarioDAO->salvarTokenRedefinicao($email, $token, $expira_em);

        $link = "http://localhost/gamehub/view/redefinir_senha.php?token=$token";
        $mensagem = "
            <h2>Recuperação de senha</h2>
            <p>Olá, clique no link abaixo para redefinir sua senha (válido por 1 hora):</p>
            <p><a href='$link'>Redefinir senha</a></p>
        ";

        EmailHelper::enviar($email, "Redefinição de Senha", $mensagem);
    }

    // Por segurança, sempre diga que "enviamos o e-mail" mesmo que não exista
    header('Location: ../view/mensagem.php?msg=Se o e-mail existir, você receberá o link');
}
