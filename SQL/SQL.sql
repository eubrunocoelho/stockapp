CREATE DATABASE stockapp;

USE stockapp;

CREATE TABLE gestor(
    ID INT NOT NULL AUTO_INCREMENT,
    nome VARCHAR(128) NOT NULL,
    email VARCHAR(128) UNIQUE NOT NULL,
    cpf INT UNIQUE NOT NULL,
    senha VARCHAR(255) NOT NULL,
    telefone VARCHAR(128),
    endereco VARCHAR(255),
    cargo INT NOT NULL,
    genero INT NOT NULL,
    status INT NOT NULL,
    img_url VARCHAR(255),
    CONSTRAINT PK_ID_gestor PRIMARY KEY(ID)
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4;