-- ============================================
-- MyWebsite Database File
-- ============================================

CREATE DATABASE IF NOT EXISTS mywebsite_db;
USE mywebsite_db;

CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Sample data (passwords are hashed versions of "password123")
INSERT INTO users (name, email, password) VALUES
('Alice Johnson', 'alice@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi'),
('Bob Smith',    'bob@example.com',   '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi');

-- ============================================
-- Add OTP columns for Forgot Password feature
-- Run this if you already have the table:
-- ============================================
ALTER TABLE users
    ADD COLUMN IF NOT EXISTS otp VARCHAR(255) DEFAULT NULL,
    ADD COLUMN IF NOT EXISTS otp_expires DATETIME DEFAULT NULL;
