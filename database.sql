CREATE DATABASE IF NOT EXISTS gym;
USE gym;

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100),
    email VARCHAR(100) UNIQUE,
    password VARCHAR(255)
);

CREATE TABLE enquiries (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    plan VARCHAR(50),
    message TEXT,
    submitted_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id)
);
