<?php
require_once '../dao/UsuarioDAO.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $token = $_POST['token'];
    $novaSenha = $_POST['nova_senha'];

    $usuarioDAO = new UsuarioDAO();
    $usuario = $usuarioDAO->verificarToken($token);

    if ($usuario) {
        $novaSenhaHash = password_hash($novaSenha, PASSWORD_DEFAULT);
        $usuarioDAO->atualizarSenha($usuario['id'], $novaSenhaHash);
        header('Location: ../view/mensagem.php?msg=Senha redefinida com sucesso!');
    } else {
        header('Location: ../view/mensagem.php?msg=Token inválido ou expirado.');
    }
}
