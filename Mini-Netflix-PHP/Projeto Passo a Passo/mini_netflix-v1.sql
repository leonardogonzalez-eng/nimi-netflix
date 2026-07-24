-- Cria o banco de dados se não existir
CREATE DATABASE IF NOT EXISTS mini_netflix CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE mini_netflix;

-- Cria a tabela de filmes
CREATE TABLE IF NOT EXISTS filmes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    titulo VARCHAR(255) NOT NULL,
    descricao TEXT,
    imagem_url VARCHAR(255),
    categoria VARCHAR(100),
    ano_lancamento INT,
    criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- Insere alguns registros de teste
INSERT INTO filmes (titulo, descricao, imagem_url, categoria, ano_lancamento) VALUES
('Stranger Things', 'Um grupo de amigos se envolve em uma série de eventos sobrenaturais na cidade de Hawkins.', 'https://images.unsplash.com/photo-1626814026160-2237a95fc5a0?q=80&w=400', 'Ficção Científica', 2016),
('Inception', 'Um ladrão que rouba segredos corporativos por meio do uso de tecnologia de compartilhamento de sonhos.', 'https://images.unsplash.com/photo-1536440136628-849c177e76a1?q=80&w=400', 'Ação / Sci-Fi', 2010),
('The Crown', 'A história da Rainha Elizabeth II e os eventos políticos e pessoais que moldaram seu reinado.', 'https://images.unsplash.com/photo-1509198397868-475647b2a1e5?q=80&w=400', 'Drama Histórico', 2016),
('Whiplash', 'Um jovem baterista promissor se matricula em um conservatório de música de corte superior.', 'https://images.unsplash.com/photo-1511671782779-c97d3d27a1d4?q=80&w=400', 'Drama / Música', 2014);