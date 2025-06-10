CREATE DATABASE IF NOT EXISTS cadastro_site CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE cadastro_site;

DROP TABLE IF EXISTS feedbacks; 
DROP TABLE IF EXISTS usuario;

CREATE TABLE usuario (
    id INT AUTO_INCREMENT PRIMARY KEY, 
    cpf VARCHAR(14) UNIQUE NOT NULL, 
    email VARCHAR(100) UNIQUE NOT NULL, 
    senha VARCHAR(255) NOT NULL, 
    data_cadastro TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE feedbacks (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT NOT NULL, 
    nome_prestador VARCHAR(100) NOT NULL,
    profissao VARCHAR(100),
    tipo VARCHAR(50),
    nota INT,
    comentario TEXT,
    data_feedback TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (usuario_id) REFERENCES usuario(id) ON DELETE CASCADE -- Garante integridade referencial
);