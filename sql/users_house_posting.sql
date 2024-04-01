-- Create database if not exists
CREATE DATABASE IF NOT EXISTS spitogatos;

-- Switch to your database
USE spitogatos;

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL
);

CREATE TABLE house_postings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    area ENUM('Αθήνα', 'Θεσσαλονίκη', 'Πάτρα', 'Ηράκλειο') NOT NULL,
    price  int  NOT NULL,
    availability ENUM('Ενοικίαση', 'Πώληση') NOT NULL,
    size INT NOT NULL,
    FOREIGN KEY (user_id) REFERENCES users(id)
);
DELIMITER //
CREATE TRIGGER enforce_price_size_check
AFTER INSERT ON house_postings
FOR EACH ROW
BEGIN
    IF NEW.price < 50 OR NEW.price > 5000000 THEN
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Price must be between 50 and 5000000';
    END IF;
    IF NEW.size < 20 OR NEW.size > 1000 THEN
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Size must be between 20 and 1000';
    END IF;
END//
DELIMITER ;
