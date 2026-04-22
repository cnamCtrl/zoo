DROP DATABASE IF EXISTS zoo_db;
CREATE DATABASE zoo_db;
USE zoo_db;

CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('Admin', 'User') DEFAULT 'User',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS enclosures (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    type VARCHAR(50) NOT NULL,
    capacity INT NOT NULL,
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS staff (
    id INT AUTO_INCREMENT PRIMARY KEY,
    first_name VARCHAR(50) NOT NULL,
    last_name VARCHAR(50) NOT NULL,
    role_title VARCHAR(50) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    phone VARCHAR(20),
    assigned_enclosure_id INT NULL,
    hire_date DATE NOT NULL,
    FOREIGN KEY (assigned_enclosure_id) REFERENCES enclosures(id) ON DELETE SET NULL
);

CREATE TABLE IF NOT EXISTS animals (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    species VARCHAR(100) NOT NULL,
    gender ENUM('Male', 'Female', 'Unknown') DEFAULT 'Unknown',
    date_of_birth DATE,
    join_date DATE NOT NULL,
    description TEXT,
    image_url VARCHAR(255) DEFAULT 'default.jpg',
    enclosure_id INT NULL,
    status ENUM('Healthy', 'Sick', 'In Treatment', 'Quarantine') DEFAULT 'Healthy',
    FOREIGN KEY (enclosure_id) REFERENCES enclosures(id) ON DELETE SET NULL
);

CREATE TABLE IF NOT EXISTS feedings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    animal_id INT NOT NULL,
    staff_id INT NOT NULL,
    feed_time DATETIME NOT NULL,
    food_type VARCHAR(100) NOT NULL,
    quantity VARCHAR(50) NOT NULL,
    status ENUM('Pending', 'Completed') DEFAULT 'Pending',
    FOREIGN KEY (animal_id) REFERENCES animals(id) ON DELETE CASCADE,
    FOREIGN KEY (staff_id) REFERENCES staff(id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS medical_records (
    id INT AUTO_INCREMENT PRIMARY KEY,
    animal_id INT NOT NULL,
    vet_id INT NOT NULL,
    checkup_date DATE NOT NULL,
    diagnosis TEXT NOT NULL,
    treatment TEXT,
    next_checkup DATE NULL,
    FOREIGN KEY (animal_id) REFERENCES animals(id) ON DELETE CASCADE,
    FOREIGN KEY (vet_id) REFERENCES staff(id) ON DELETE CASCADE
);

-- Insert a default admin user (password: admin123)
-- The password hash here is for 'admin123' generated via password_hash('admin123', PASSWORD_BCRYPT)
INSERT INTO users (username, email, password, role) 
VALUES ('admin', 'admin@zoo.com', '$2y$10$PXf5hP2kW1n2vTAAem4ej.e0hHkx0XZLXYGkrEpj0xmOoEcCIfY5C', 'Admin')
ON DUPLICATE KEY UPDATE username='admin';
