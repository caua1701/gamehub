<?php
session_start();

if (!isset($_SESSION['user'])) {
    header('Location: view/login.php');
    exit;
} else {
    header('Location: view/perfil.php');
    exit;
}
