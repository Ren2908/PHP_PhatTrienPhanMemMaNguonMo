DROP DATABASE IF EXISTS shopdb;
CREATE DATABASE shopdb;
USE shopdb;

CREATE TABLE products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    price DECIMAL(10,2) NOT NULL
);

INSERT INTO products (name, price) VALUES
('Apple iPhone 14', 29999),
('Samsung Galaxy S23', 25999),
('Xiaomi Redmi Note 12', 7999),
('Sony WH-1000XM5', 8499),
('Apple MacBook Air M2', 35999),
('Dell XPS 13', 32999),
('Asus ZenBook 14', 21999);
CREATE TABLE customers (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    phone VARCHAR(20)
);

INSERT INTO customers (name, phone) VALUES
('Nguyen Van A', '0901234567'),
('Tran Thi B', '0912345678'),
('Le Van C', '0923456789');

CREATE TABLE orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    product_name VARCHAR(100) NOT NULL,
    customer_name VARCHAR(100) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);