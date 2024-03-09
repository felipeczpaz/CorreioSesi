CREATE DATABASE IF NOT EXISTS correio_sesi;

USE correio_sesi;

-- Create User table
CREATE TABLE user (
    user_id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL,
    password_hash VARCHAR(255) NOT NULL,
    creation_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Create Posts table
CREATE TABLE posts (
    post_id INT AUTO_INCREMENT PRIMARY KEY,
    author_id INT,
    creation_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    is_anonymous BOOLEAN NOT NULL DEFAULT FALSE,
    post_content TEXT,
    FOREIGN KEY (author_id) REFERENCES user(user_id)
);

-- Add index on author_id in posts table
CREATE INDEX idx_author_id ON posts (author_id);
