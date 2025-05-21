CREATE DATABASE gamehub;

USE gamehub;

CREATE TABLE usuario (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    senha VARCHAR(255) NOT NULL,
    token_recuperacao VARCHAR(255),
    token_expiracao DATETIME
);