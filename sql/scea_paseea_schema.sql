CREATE DATABASE scea_db;
USE scea_paseea;

-- Creating table for companies
CREATE TABLE companies (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    province ENUM('Kadiogo', 'Haut-Bassins', 'Kourwéogo') NOT NULL,
    registration_number VARCHAR(100) NOT NULL UNIQUE,
    accreditation_date DATE NOT NULL,
    status ENUM('Actif', 'En attente', 'Suspendu') DEFAULT 'En attente',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT chk_name_length CHECK (LENGTH(name) >= 2)
);

-- Creating table for households
CREATE TABLE households (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    phone VARCHAR(20),
    address TEXT NOT NULL,
    province ENUM('Kadiogo', 'Haut-Bassins', 'Kourwéogo') NOT NULL,
    eligibility_status ENUM('Éligible', 'Non éligible') NOT NULL,
    member_count INT NOT NULL,
    latrine_status ENUM('Non construit', 'En construction', 'Terminé') DEFAULT 'Non construit',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT chk_phone_valid CHECK (phone REGEXP '^[0-9]{10,15}$|^$'),
    CONSTRAINT chk_member_count CHECK (member_count >= 0)
);

-- Creating table for surveys
CREATE TABLE surveys (
    id INT AUTO_INCREMENT PRIMARY KEY,
    survey_id VARCHAR(50) NOT NULL UNIQUE,
    household_id INT NOT NULL,
    survey_date DATE NOT NULL,
    status ENUM('Terminée', 'En cours') DEFAULT 'En cours',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (household_id) REFERENCES households(id) ON DELETE CASCADE
);

-- Creating table for coupons
CREATE TABLE coupons (
    id INT AUTO_INCREMENT PRIMARY KEY,
    coupon_code VARCHAR(50) NOT NULL UNIQUE,
    type ENUM('Latrine', 'Assainissement') NOT NULL,
    province ENUM('Kadiogo', 'Haut-Bassins', 'Kourwéogo') NOT NULL,
    expiration_date DATE NOT NULL,
    status ENUM('Actif', 'En attente', 'Utilisé', 'Expiré', 'En construction') DEFAULT 'En attente',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Creating table for coupon distributions
CREATE TABLE coupon_distributions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    coupon_id INT NOT NULL,
    household_id INT NOT NULL,
    distribution_date DATE NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (coupon_id) REFERENCES coupons(id) ON DELETE CASCADE,
    FOREIGN KEY (household_id) REFERENCES households(id) ON DELETE CASCADE
);

-- Creating table for coupon transfers
CREATE TABLE coupon_transfers (
    id INT AUTO_INCREMENT PRIMARY KEY,
    coupon_id INT NOT NULL,
    source_household_id INT NOT NULL,
    destination_household_id INT NOT NULL,
    transfer_date DATE NOT NULL,
    reason TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (coupon_id) REFERENCES coupons(id) ON DELETE CASCADE,
    FOREIGN KEY (source_household_id) REFERENCES households(id) ON DELETE CASCADE,
    FOREIGN KEY (destination_household_id) REFERENCES households(id) ON DELETE CASCADE
);

-- Creating table for notifications
CREATE TABLE notifications (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    message TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    is_read BOOLEAN DEFAULT FALSE
);

-- Creating table for company documents
CREATE TABLE company_documents (
    id INT AUTO_INCREMENT PRIMARY KEY,
    company_id INT NOT NULL,
    document_path VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (company_id) REFERENCES companies(id) ON DELETE CASCADE
);