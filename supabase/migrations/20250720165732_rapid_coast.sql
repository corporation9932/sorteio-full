-- Criação do banco de dados
CREATE DATABASE IF NOT EXISTS rifas_system CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE rifas_system;

-- Tabela de administradores
CREATE TABLE IF NOT EXISTS admins (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    email VARCHAR(100) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tabela de rifas/campanhas
CREATE TABLE IF NOT EXISTS campaigns (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    description TEXT,
    image VARCHAR(255),
    price DECIMAL(10,2) NOT NULL,
    total_numbers INT NOT NULL,
    sold_numbers INT DEFAULT 0,
    min_purchase INT DEFAULT 1,
    max_purchase INT DEFAULT 500,
    draw_date DATETIME,
    status ENUM('active', 'paused', 'finished', 'cancelled') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Tabela de descontos
CREATE TABLE IF NOT EXISTS discounts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    campaign_id INT,
    quantity INT NOT NULL,
    discount_amount DECIMAL(10,2) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (campaign_id) REFERENCES campaigns(id) ON DELETE CASCADE
);

-- Tabela de usuários
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    firstname VARCHAR(100) NOT NULL,
    lastname VARCHAR(100) NOT NULL,
    phone VARCHAR(20) UNIQUE NOT NULL,
    email VARCHAR(100),
    cpf VARCHAR(14),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tabela de pedidos
CREATE TABLE IF NOT EXISTS orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    campaign_id INT,
    quantity INT NOT NULL,
    total_amount DECIMAL(10,2) NOT NULL,
    payment_id VARCHAR(100),
    payment_status ENUM('pending', 'paid', 'cancelled', 'expired') DEFAULT 'pending',
    order_token VARCHAR(100) UNIQUE NOT NULL,
    pix_code TEXT,
    qr_code TEXT,
    expires_at DATETIME,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (campaign_id) REFERENCES campaigns(id)
);

-- Tabela de números da rifa
CREATE TABLE IF NOT EXISTS raffle_numbers (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT,
    campaign_id INT,
    number INT NOT NULL,
    status ENUM('reserved', 'paid', 'available') DEFAULT 'reserved',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE,
    FOREIGN KEY (campaign_id) REFERENCES campaigns(id) ON DELETE CASCADE,
    UNIQUE KEY unique_campaign_number (campaign_id, number)
);

-- Tabela de ranking
CREATE TABLE IF NOT EXISTS ranking (
    id INT AUTO_INCREMENT PRIMARY KEY,
    campaign_id INT,
    user_id INT,
    total_numbers INT DEFAULT 0,
    position INT,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (campaign_id) REFERENCES campaigns(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    UNIQUE KEY unique_campaign_user (campaign_id, user_id)
);

-- Tabela de contatos
CREATE TABLE IF NOT EXISTS contacts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    phone VARCHAR(20) NOT NULL,
    campaign VARCHAR(255),
    subject VARCHAR(255),
    message TEXT NOT NULL,
    status ENUM('new', 'read', 'replied') DEFAULT 'new',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Inserir admin padrão (senha: admin123)
INSERT INTO admins (username, password, email) VALUES 
('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin@gemeosbrasil.me');

-- Inserir campanha de exemplo
INSERT INTO campaigns (title, description, image, price, total_numbers, draw_date, min_purchase, max_purchase) VALUES 
('EDIÇÃO 08 - CEGONHA MILIONÁRIA', 'são 8 caminhonetas + cegonha para sua mudança de vida', 'cegonha caminhonetas.jpg', 5.00, 1000000, '2024-12-31 19:00:00', 1, 500);

-- Inserir descontos de exemplo
INSERT INTO discounts (campaign_id, quantity, discount_amount) VALUES 
(1, 50, 30.00),
(1, 100, 80.00);

-- Inserir dados de ranking de exemplo
INSERT INTO users (firstname, lastname, phone) VALUES 
('Jose', 'Silva', '(11) 99999-9999'),
('Sun', 'Santos', '(11) 88888-8888'),
('Marcelo', 'Oliveira', '(11) 77777-7777');

INSERT INTO ranking (campaign_id, user_id, total_numbers, position) VALUES 
(1, 1, 402, 1),
(1, 2, 295, 2),
(1, 3, 260, 3);