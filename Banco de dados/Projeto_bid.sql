create database Projeto_bid;
use Projeto_bid;
 
CREATE TABLE IF NOT EXISTS USUARIOS ( 
    id_usuarios INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    senha VARCHAR(255) NOT NULL,
    data_cadastro DATETIME DEFAULT CURRENT_TIMESTAMP, 
    ultima_atualizacao DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

INSERT IGNORE INTO USUARIOS (nome, email, senha) 
    VALUES ('Administrador', 'root@admin.com', '12345'); 
    
CREATE TABLE IF NOT EXISTS jogadores (
    id_jogadores INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(120) NOT NULL,
    posicao ENUM(
        'Goleiro', 'Lateral Direito', 'Lateral Esquerdo', 'Zagueiro', 
        'Volante', 'Meia', 'Atacante', 'Ponta Direita', 
        'Ponta Esquerda', 'Centroavante'
    ) NOT NULL,
    categoria ENUM(
        'Profissional', 'Sub-23', 'Sub-20', 'Sub-17', 'Sub-15', 'Sub-13'
    ) NOT NULL,
    idade INT NOT NULL,
    clube VARCHAR(120) NOT NULL,
    nacionalidade VARCHAR(80) NOT NULL,
    pe_dominante ENUM('Direito', 'Esquerdo', 'Ambidestro') NOT NULL,
    altura_cm INT NOT NULL,
    peso_kg INT NOT NULL,
    status ENUM('Ativo', 'Suspenso', 'Lesionado', 'Inativo') NOT NULL DEFAULT 'Ativo',
    obs VARCHAR(100),
    cadastrado_por INT,
    criado_em DATE NOT NULL DEFAULT (CURRENT_DATE),
    atualizado_em DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT fk_jogador_usuario FOREIGN KEY (cadastrado_por) REFERENCES USUARIOS(id_usuarios) ON DELETE SET NULL
);

INSERT INTO jogadores (
    nome, 
    posicao, 
    categoria, 
    idade, 
    clube, 
    nacionalidade, 
    pe_dominante, 
    altura_cm, 
    peso_kg, 
    status, 
    cadastrado_por,
    obs
) VALUES 
(
    'Carlos Eduardo Silva',
    'Atacante',
    'Profissional',
    24,
    'Esporte Clube Vitória',
    'Brasileiro',
    'Direito',
    178,
    74,
    'Ativo',
    1,
    'Jogador veloz com bom drible.'
);

select * from USUARIOS;
UPDATE USUARIOS SET nivel = 'admin' WHERE email = 'mateus123339@gmail.com';

select * from jogadores;