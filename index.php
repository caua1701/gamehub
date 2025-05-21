<?php
session_start();

if (!isset($_SESSION['usuario_id'])) {
    header('Location: view/login.php');
    exit;
} else {
    header('Location: view/perfil.php');
    exit;
}
