CREATE DATABASE IF NOT EXISTS cadastro_site;
USE cadastro_site;

DROP TABLE IF EXISTS prestadores;

CREATE TABLE prestadores (
    cpf VARCHAR(14) PRIMARY KEY NOT NULL,
    nome VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    profissao VARCHAR(100) NOT NULL, -- NOVO CAMPO ADICIONADO AQUI
    arquivo VARCHAR(255),
    mensagem TEXT,
    data_cadastro TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);