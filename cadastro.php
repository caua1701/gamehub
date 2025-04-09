<?php
session_start();
// include 'auth.php'; //Verificar se o usuário está on

//Atualização de segurança: Definir cabeçalhos para evitar problemas
//encoding
header('Content-Type: text/html; charset=utf-8');

//Função para atualizar a entrada de dados
function sanitize_input( $data ) {
    //Remover espaços no início e final e converter caracteres especiais
    //em entidade HTML
    return htmlspecialchars(trim($data));
}
//Função para validar o e-mail
function validate_email( $email ) {
    return filter_var(trim($email), FILTER_VALIDATE_EMAIL);
}
//Verificando se o formulário foi enviado
if($_SERVER['REQUEST_METHOD'] == 'POST'){
    //Filtrando e sanitizando os dados recebidos
    $username = sanitize_input( $_POST['username'] );
    $email = sanitize_input( $_POST['email'] );
    $senha = sanitize_input( $_POST['senha'] );

    //Validações simples
    if(empty($username) || empty($email) || empty($senha)){ 
        echo'Favor, preencher todos os campos obrigatórios.';
        exit();
    }
    //Valida o formato do e-mail
    if(!validate_email($email)){
        echo 'Favor, insira um e-mail válido';
        exit();
    }
    //Conexão com o banco de dados utilizando PDO
    try {
        $pdo = new PDO('mysql:host=localhost;dbname=gamehub', 'root','senac');
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        //Preparar a consulta SQL para inserção do banco
        $stmt = $pdo->prepare("INSERT INTO clientes(nome,email, senha)
        VALUES (:nome,:email,:senha);");
        $stmt->bindParam("username", $username);
        $stmt->bindParam("email", $email);
        //Gerar o hash da senha antes de passar o bindParam
        $senhaHash = password_hash($senha, PASSWORD_DEFAULT);
        $stmt->bindParam("senha", $senhaHash);//Usando o hash da senha

        //Executar a consulta
        $stmt->execute();

        echo "Cadastro realizado com sucesso!";
        header("Location: login.html");
    } catch (Exception $e) {
        echo "Erro ao conectar com o banco de dados". $e->getMessage();
    }
}
?>
