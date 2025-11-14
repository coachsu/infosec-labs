CREATE DATABASE IF NOT EXISTS userdb CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE userdb;

DROP TABLE IF EXISTS users;
CREATE TABLE users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100),
  username VARCHAR(50) UNIQUE,
  password VARCHAR(255),
  email VARCHAR(100),          
  role ENUM('admin','user','guest') DEFAULT 'user',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

INSERT INTO users (name, username, password, email, role) VALUES
('Alice Admin','admin','secret123','alice.admin@example.com','admin'),
('Bob Student','bob','bobpass','bob@student.com','user'),
('Charlie','charlie','charliepass','charlie@test.com','user'),
('David Guest','david','davidpadd','david@example.com','guest'),
('Eva Teacher','eva','evapass','eva@school.edu','admin'),
('Frank Tester','frank','frankpass','frank@demo.net','user');
