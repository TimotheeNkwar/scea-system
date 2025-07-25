CREATE DATABASE IF NOT EXISTS scea_main;
USE scea_main;
CREATE TABLE IF NOT EXISTS roles(
    row_id INT AUTO_INCREMENT PRIMARY KEY,
    role_id VARCHAR(5),
    role_name VARCHAR(20)
);
INSERT INTO roles(role_id, role_name)
VALUES
("01", "Super-Admin"),
("02", "Admin"),
("03", "Agent"),
("04", "company"),
("05", "Householder");
CREATE TABLE IF NOT EXISTS provinces(
    row_id INT AUTO_INCREMENT PRIMARY KEY,
    province_id VARCHAR(20),
    province_name VARCHAR(30),
    statut INT DEFAULT 1,
    visible BOOLEAN DEFAULT TRUE
);
CREATE TABLE IF NOT EXISTS cities(
    row_id INT AUTO_INCREMENT PRIMARY KEY,
    city_id VARCHAR(10),
    city_name VARCHAR(30),
    province_id VARCHAR(20),
    statut INT DEFAULT 1,
    visible BOOLEAN DEFAULT TRUE,
    FOREIGN KEY (province_id) REFERENCES provinces(province_id)
);
CREATE TABLE IF NOT EXISTS persons(
    row_id INT AUTO_INCREMENT PRIMARY KEY,
    person_short_id VARCHAR(20) UNIQUE,
    person_id VARCHAR(40) UNIQUE,
    person_short_idh VARCHAR(200) UNIQUE,
    person_idh VARCHAR(200) UNIQUE,
    first_name VARCHAR(20),
    second_name VARCHAR(20),
    third_name VARCHAR(20),
    birthdate DATE,
    birthplace VARCHAR(30),
    gender INT DEFAULT 1,
    email VARCHAR(40) UNIQUE,
    phone_number_1 BIGINT UNIQUE,
    phone_number_2 BIGINT UNIQUE,
    role_id VARCHAR(5),
    enlisment_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    temp_id VARCHAR(200) UNIQUE,
    statut INT DEFAULT 1,
    visible BOOLEAN DEFAULT TRUE,
    FOREIGN KEY (role_id) REFERENCES roles(role_id)
);
CREATE TABLE IF NOT EXISTS coupons(
    row_id INT AUTO_INCREMENT PRIMARY KEY,
    coupon_short_id VARCHAR(20) UNIQUE,
    coupon_id VARCHAR(50) UNIQUE,
    coupon_idh VARCHAR(200) UNIQUE,
    coupon_type INT DEFAULT 1,
    person_id VARCHAR(40),
    household_id VARCHAR(40) DEFAULT NULL,
    company_id VARCHAR(40) DEFAULT NULL,
    create_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    city_id VARCHAR(10) DEFAULT NULL,
    enlisment_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    temp_id VARCHAR(200) UNIQUE,
    statut INT DEFAULT 2,
    visible BOOLEAN DEFAULT TRUE,
    FOREIGN KEY (person_id) REFERENCES persons(person_id),
    FOREIGN KEY (household_id) REFERENCES households(household_id),
    FOREIGN KEY (company_id) REFERENCES companies(company_id),
    FOREIGN KEY (city_id) REFERENCES cities(city_id),
    FOREIGN KEY (coupon_type) REFERENCES products(product_id)
);

CREATE TABLE IF NOT EXISTS products(
    row_id INT AUTO_INCREMENT PRIMARY KEY,
    product_id INT,
    product_name VARCHAR(30),
    statut INT DEFAULT 1,
    visible BOOLEAN DEFAULT TRUE
);

CREATE TABLE IF NOT EXISTS households(
    row_id INT AUTO_INCREMENT PRIMARY KEY,
    household_short_id VARCHAR(20) UNIQUE,
    household_id VARCHAR(40) UNIQUE,
    household_short_idh VARCHAR(200) UNIQUE,
    household_idh VARCHAR(200) UNIQUE,
    household_int_id BIGINT UNIQUE,
    longitude DECIMAL(9, 9),
    latitude DECIMAL(9, 9),
    person_id VARCHAR(40),
    city_id VARCHAR(10),
    municipality VARCHAR(25),
    district VARCHAR(25),
    street VARCHAR(25),
    house_number INT,
    total_people INT,
    income_source VARCHAR(100),
    house_type VARCHAR(200),
    basic_service VARCHAR(200),
    latrine BOOLEAN DEFAULT 0,
    latrine_type VARCHAR(20),
    photo BLOB,
    shared_latrine VARCHAR(100),
    wastes_management TEXT,
    hand_washing TEXT,
    product_id INT,
    barrier TEXT,
    project_knowledge TEXT,
    household_motivation TEXT,
    comment TEXT,
    enlistement_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    temp_id VARCHAR(200) UNIQUE,
    statut INT DEFAULT 0,
    visible BOOLEAN DEFAULT TRUE,
    FOREIGN KEY (person_id) REFERENCES persons(person_id),
    FOREIGN KEY (city_id) REFERENCES cities(city_id),
    FOREIGN KEY (product_id) REFERENCES products(product_id)
);
CREATE TABLE IF NOT EXISTS household_member(
    row_id INT AUTO_INCREMENT PRIMARY KEY,
    person_id VARCHAR(40),
    household_id VARCHAR(40),
    eduction_level VARCHAR(20),
    occupation VARCHAR(30),
    statut INT DEFAULT 1,
    visible BOOLEAN DEFAULT TRUE,
    FOREIGN KEY (person_id) REFERENCES persons(person_id),
    FOREIGN KEY (household_id) REFERENCES households(household_id)
);
CREATE TABLE IF NOT EXISTS companies(
    row_id INT AUTO_INCREMENT PRIMARY KEY,
    company_short_id VARCHAR(20) UNIQUE,
    company_id VARCHAR(40) UNIQUE,
    company_short_idh VARCHAR(200) UNIQUE,
    company_idh VARCHAR(200) UNIQUE,
    name VARCHAR(25),
    address VARCHAR(200),
    contact BIGINT UNIQUE,
    email VARCHAR(30) UNIQUE,
    city_id VARCHAR(10),
    municipality VARCHAR(25),
    district VARCHAR(25),
    street VARCHAR(25),
    house_number INT,
    person_id VARCHAR(40),
    documents BLOB,
    rccm VARCHAR(50),
    id_fss BIGINT,
    info TEXT,
    enrolement_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    agent_id VARCHAR(40),
    temp_id VARCHAR(200) UNIQUE,
    statut INT DEFAULT 1,
    visible BOOLEAN DEFAULT TRUE,
    FOREIGN KEY (city_id) REFERENCES cities(city_id),
    FOREIGN KEY (person_id) REFERENCES persons(person_id),
    FOREIGN KEY (agent_id) REFERENCES persons(person_id)
);
CREATE TABLE IF NOT EXISTS employees(
    row_id INT AUTO_INCREMENT PRIMARY KEY,
    person_id VARCHAR(40) UNIQUE,
    address VARCHAR(200),
    etat_civil INT DEFAULT 1,
    admin_id VARCHAR(40),
    province_id VARCHAR(20) NOT NULL,
    statut INT DEFAULT 1,
    visible BOOLEAN DEFAULT TRUE,
    FOREIGN KEY (person_id) REFERENCES persons(person_id),
    FOREIGN KEY (admin_id) REFERENCES persons(person_id),
    FOREIGN KEY (province_id) REFERENCES provinces(province_id)
);

CREATE TABLE IF NOT EXISTS survey(
    row_id INT AUTO_INCREMENT PRIMARY KEY,
    coupon_id VARCHAR(50),
    person_id VARCHAR(40),
    note TEXT,
    survey_time TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    statut INT DEFAULT 1,
    visible BOOLEAN DEFAULT TRUE,
    FOREIGN KEY (coupon_id) REFERENCES coupons(coupon_id),
    FOREIGN KEY (person_id) REFERENCES persons(person_id)
);

CREATE TABLE IF NOT EXISTS coupon_transfert(
    row_id INT AUTO_INCREMENT PRIMARY KEY,
    transfert_short_id VARCHAR(20) UNIQUE,
    transfert_id VARCHAR(40) UNIQUE,
    transfert_short_idh VARCHAR(200),
    transfert_idh VARCHAR(200),
    from_household_id VARCHAR(40),
    to_household_id VARCHAR(40),
    coupon_id VARCHAR(50),
    transfert_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    reason TEXT,
    temp_id VARCHAR(200) UNIQUE,
    statut INT DEFAULT 1,
    visible BOOLEAN DEFAULT TRUE,
    FOREIGN KEY (from_household_id) REFERENCES households(household_id),
    FOREIGN KEY (to_household_id) REFERENCES household(household_id),
    FOREIGN KEY (coupon_id) REFERENCES coupons(coupon_id)
);

CREATE TABLE IF NOT EXISTS reports(
    row_id INT AUTO_INCREMENT PRIMARY KEY,
    report_short_id VARCHAR(20),
    report_id VARCHAR(40),
    person_id VARCHAR(40),
    name VARCHAR(130),
    type INT,
    from_date TIMESTAMP,
    to_date TIMESTAMP,
    period_name VARCHAR(20),
    create_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    comments TEXT,
    download INT DEFAULT 0,
    temp_id VARCHAR(200) UNIQUE,
    statut INT DEFAULT 1,
    visible BOOLEAN DEFAULT TRUE,
    FOREIGN KEY (person_id) REFERENCES persons(person_id)
);

CREATE TABLE IF NOT EXISTS confirm_mail (
    row_id INT AUTO_INCREMENT PRIMARY KEY,
    person_id VARCHAR(40) UNIQUE NOT NULL,
    generated_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    code BIGINT NOT NULL CHECK (code >= 100000 AND code <= 999999),
    valid BOOLEAN DEFAULT TRUE,
    FOREIGN KEY (person_id) REFERENCES persons(person_id)
);

CREATE TABLE IF NOT EXISTS login(
    row_id INT AUTO_INCREMENT PRIMARY KEY,
    person_id VARCHAR(40) UNIQUE,
    role_id VARCHAR(5),
    password VARCHAR(200),
    cdown INT DEFAULT 5, 
    assign_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    statut INT DEFAULT 1,
    visible BOOLEAN DEFAULT TRUE,
    FOREIGN KEY (person_id) REFERENCES persons(person_id),
    FOREIGN KEY (role_id) REFERENCES roles(role_id)
);
CREATE TABLE old_logs(
    row_id INT AUTO_INCREMENT PRIMARY KEY,
    person_id VARCHAR(40),
    password VARCHAR(200),
    assign_date TIMESTAMP,
    change_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    statut INT DEFAULT 1,
    visible BOOLEAN DEFAULT TRUE,
    FOREIGN KEY (person_id) REFERENCES persons(person_id)
);



SELECT * FROM persons;