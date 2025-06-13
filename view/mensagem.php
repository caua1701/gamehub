<?php
//Recebe a mensagem pela URL
if (!$_GET === null || !$_GET == "") {
    $mensagem = htmlspecialchars($_GET['msg']);
}
else {
    $mensagem = "Nenhuma mensagem encontrada.";
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Mensagem</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f2f2f2;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .caixa {
            background-color: #fff;
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 0 10px rgba(0,0,0,0.2);
            text-align: center;
            max-width: 500px;
        }
        .caixa h2 {
            color: #333;
        }
        .botao-voltar {
            margin-top: 20px;
            display: inline-block;
            padding: 10px 25px;
            background-color: #007bff;
            color: white;
            text-decoration: none;
            border-radius: 6px;
        }
        .botao-voltar:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="caixa">
        <h2><?php echo $mensagem; ?></h2>
        <a href="/gamehub/login" class="botao-voltar">Voltar para o login</a>
    </div>
</body>
</html>
