CREATE DATABASE IF NOT EXISTS loja_perifericos CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE loja_perifericos;


CREATE TABLE usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100),
    email VARCHAR(150) UNIQUE,
    senha_hash VARCHAR(255),
    tipo ENUM('cliente','funcionario','admin') NOT NULL DEFAULT 'cliente',
    criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);


CREATE TABLE categorias (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL
);


CREATE TABLE produtos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(150) NOT NULL,
    descricao TEXT,
    preco DECIMAL(10,2) NOT NULL,
    estoque INT DEFAULT 0,
    categoria_id INT,
    imagem VARCHAR(255),
    criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (categoria_id) REFERENCES categorias(id) ON DELETE SET NULL
);


INSERT INTO categorias (nome) VALUES 
('Teclados'),
('Mouses'),
('Headsets'),
('Monitores'),
('Gabinetes'),
('Mousepads');


CREATE TABLE IF NOT EXISTS wishlists (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    product_id INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY uq_user_product (user_id, product_id),
    INDEX idx_user (user_id),
    INDEX idx_product (product_id)
);
