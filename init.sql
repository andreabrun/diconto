-- Create the database
CREATE DATABASE IF NOT EXISTS diconto;

-- Create a dedicated user with password
CREATE USER 'diconto'@'localhost' IDENTIFIED BY 'DicontoPswd!123';

-- Grant privileges on the diconto database
GRANT ALL PRIVILEGES ON diconto.* TO 'diconto'@'localhost';

-- Apply changes
FLUSH PRIVILEGES;

USE diconto;

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin', 'user') DEFAULT 'user'
);

INSERT INTO users (username, password, role) 
VALUES ('admin', SHA2('password', 256), 'admin');

CREATE TABLE settlements (
    id INT AUTO_INCREMENT PRIMARY KEY,
    date DATE,
    createdAt TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE expenses (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user INT,
    label VARCHAR(255),
    date DATE,
    amount DECIMAL(10,2),
    settlement INT NULL, 
    createdAt TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    
    CONSTRAINT fk_expenses_user
        FOREIGN KEY (user) REFERENCES users(id)
        ON DELETE CASCADE,

    CONSTRAINT fk_expenses_settlement
        FOREIGN KEY (settlement) REFERENCES settlements(id)
        ON DELETE SET NULL
);

CREATE TABLE loans (
    id INT AUTO_INCREMENT PRIMARY KEY,
    userL INT,
    userB INT,
    label VARCHAR(255),
    date DATE,
    amount DECIMAL(10,2),
    settlement INT NULL, 
    createdAt TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    
    CONSTRAINT fk_expenses_userL
        FOREIGN KEY (userL) REFERENCES users(id)
        ON DELETE SET NULL,

    CONSTRAINT fk_expenses_userB
        FOREIGN KEY (userB) REFERENCES users(id)
        ON DELETE SET NULL
);