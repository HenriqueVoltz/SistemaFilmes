-- Script de criação do banco de dados para o sistema de filmes
CREATE DATABASE IF NOT EXISTS sistema_filmes;
USE sistema_filmes;

-- Tabela de usuários
CREATE TABLE IF NOT EXISTS usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario VARCHAR(50) NOT NULL UNIQUE,
    senha VARCHAR(255) NOT NULL
);

CREATE TABLE IF NOT EXISTS generos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    genero VARCHAR(50) NOT NULL UNIQUE
);


-- Tabela de filmes
CREATE TABLE IF NOT EXISTS filmes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    titulo VARCHAR(100) NOT NULL,
    diretor VARCHAR(100) NOT NULL,
    genero_id INT NOT NULL,
    duracao INT NOT NULL,
    ano_lancamento INT NOT NULL,
    plataforma ENUM('streaming', 'cinema', 'ambos') NOT NULL,
    FOREIGN KEY (genero_id) REFERENCES generos(id)
);
