<?php
session_start();
session_unset();  // Remove todas as variáveis de sessão
session_destroy(); // Destrói a sessão

// Redireciona para login, sem verificar login de novo
header('Location: login.php');
exit;
