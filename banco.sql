-- Projeto utilizando MySQLi
create schema gamehub;

use gamehub;

create table usuarios(
	id int auto_increment primary key,
    username varchar(100),
    email varchar(100),
    senha varchar(255)        
);


INSERT INTO usuarios(nome,email,sexo,telefone,endereco,cidade,estado,bairro,pais,login,senha)VALUES
(?,?,?,?,?,?,?,?,?,?,?);

select * from usuarios;
-- nova tabela para uso de PDO