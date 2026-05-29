CREATE DATABASE IF NOT EXISTS my_db;
USE my_db;

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    login VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    fullname VARCHAR(100) NOT NULL,
    data_r DATE NOT NULL,
    phone VARCHAR(20) NOT NULL,
    email VARCHAR(100) NOT NULL,
    role ENUM('user', 'admin') DEFAULT 'user'
);

CREATE TABLE applications (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    transport_name VARCHAR(100) NOT NULL,
    start_date DATE NOT NULL,
    payment_method ENUM('cash', 'transfer') NOT NULL,
    status ENUM('Новая', 'Идет обучение', 'Обучение завершено') DEFAULT 'Новая',
    review TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

INSERT INTO users (login, password, fullname, data_r, phone, email, role) 
VALUES ('Admin26', 'demo123', 'Администратор', '2000-01-01', '8(000)000-00-00', 'admin@vodit.ru', 'admin');