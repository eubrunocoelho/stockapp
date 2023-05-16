CREATE DATABASE stockapp;

USE stockapp;

CREATE TABLE gestor(
    ID INT NOT NULL AUTO_INCREMENT,
    nome VARCHAR(128) NOT NULL,
    email VARCHAR(128) UNIQUE NOT NULL,
    cpf VARCHAR(64) UNIQUE NOT NULL,
    senha VARCHAR(255) NOT NULL,
    telefone VARCHAR(128),
    endereco VARCHAR(255),
    cargo INT NOT NULL,
    genero INT NOT NULL,
    status INT NOT NULL,
    img_url VARCHAR(255),
    CONSTRAINT PK_ID_gestor PRIMARY KEY(ID)
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4;

CREATE TABLE livro(
    ID INT NOT NULL AUTO_INCREMENT,
    titulo VARCHAR(255) NOT NULL,
    formato VARCHAR(128),
    ano_publicacao YEAR NOT NULL,
    isbn VARCHAR(64) NOT NULL,
    edicao INT,
    idioma VARCHAR(128) NOT NULL,
    paginas INT,
    descricao TEXT,
    unidades INT DEFAULT 0,
    adicionado_em DATETIME NOT NULL,
    CONSTRAINT PK_ID_livro PRIMARY KEY(ID)
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4;

CREATE TABLE editora(
    ID INT NOT NULL AUTO_INCREMENT,
    nome VARCHAR(255) NOT NULL,
    CONSTRAINT PK_ID_editora PRIMARY KEY(ID)
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4;

CREATE TABLE autor(
    ID INT NOT NULL AUTO_INCREMENT,
    nome VARCHAR(255) NOT NULL,
    CONSTRAINT PK_ID_autor PRIMARY KEY(ID)
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4;

CREATE TABLE livro_editora(
    ID_livro INT NOT NULL,
    ID_editora INT NOT NULL,
    CONSTRAINT ID_livro_livro_editora FOREIGN KEY(ID_livro) REFERENCES livro(ID),
    CONSTRAINT ID_editora_livro_editora FOREIGN KEY(ID_editora) REFERENCES editora(ID)
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4;

CREATE TABLE livro_autor(
    ID_livro INT NOT NULL,
    ID_autor INT NOT NULL,
    CONSTRAINT ID_livro_livro_autor FOREIGN KEY(ID_livro) REFERENCES livro(ID),
    CONSTRAINT ID_autor_livro_autor FOREIGN KEY(ID_autor) REFERENCES autor(ID)
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4;