<?php
session_start();
include 'config.php';

if($_SERVER['REQUEST_METHOD'] == "POST"){
    $email = $_POST['email'];
    $senha = $_POST['senha'];

    try{
        //preparar a consulta para verificar o usuário
        $sql = "SELECT id, email, senha FROM clientes WHERE email = :email"; 
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':login',$usuario);
        $stmt->execute();

        //Verificar se o usuário existe
        if($stmt->rowCount() == 1){
            //Recuperar os dados do usuário
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $id = $row["id"];
            $hashed_password = $row['senha'];
            
            //Verificar se a senha corresponde
            if(password_verify($senha,$hashed_password)){
                //Senha correta, inicia a sessão
                $_SESSION['loggedin'] = true;
                $_SESSION['id'] = $id;
                $_SESSION['login'] = $login;

                //Redirecionar para a pagina incial ( nosso caso a pagina)
                header("Location:perfil.html");
                exit;
            } else{
                //Senha Incorreta
                throw new Exception("Senha incorreta!");
            }

        } else {
            throw new Exception("Usuário não encontrado!");
        }
    } catch (PDOException $e) {
        echo "Erro: ".$e->getMessage();
    }
}
?>
