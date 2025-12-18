-- Prison Management System Database Schema

CREATE DATABASE IF NOT EXISTS prison_management;
USE prison_management;

-- Users table (for authentication)
CREATE TABLE IF NOT EXISTS users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(50) UNIQUE NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin', 'user') DEFAULT 'user',
    full_name VARCHAR(100) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    last_login TIMESTAMP NULL,
    is_active BOOLEAN DEFAULT TRUE
);

-- Cells table (created before inmates due to foreign key)
CREATE TABLE IF NOT EXISTS cells (
    id INT PRIMARY KEY AUTO_INCREMENT,
    cell_number VARCHAR(20) UNIQUE NOT NULL,
    block_name VARCHAR(50) NOT NULL,
    capacity INT NOT NULL DEFAULT 1,
    current_occupancy INT DEFAULT 0,
    cell_type ENUM('single', 'shared', 'isolation') DEFAULT 'shared',
    status ENUM('available', 'occupied', 'maintenance') DEFAULT 'available',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Staff table
CREATE TABLE IF NOT EXISTS staff (
    id INT PRIMARY KEY AUTO_INCREMENT,
    staff_id VARCHAR(20) UNIQUE NOT NULL,
    first_name VARCHAR(50) NOT NULL,
    last_name VARCHAR(50) NOT NULL,
    email VARCHAR(100),
    phone VARCHAR(20),
    position VARCHAR(50) NOT NULL,
    department VARCHAR(50),
    hire_date DATE NOT NULL,
    salary DECIMAL(10, 2),
    status ENUM('active', 'inactive', 'suspended') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Inmates table
CREATE TABLE IF NOT EXISTS inmates (
    id INT PRIMARY KEY AUTO_INCREMENT,
    inmate_id VARCHAR(20) UNIQUE NOT NULL,
    first_name VARCHAR(50) NOT NULL,
    last_name VARCHAR(50) NOT NULL,
    date_of_birth DATE NOT NULL,
    gender ENUM('Male', 'Female', 'Other') NOT NULL,
    nationality VARCHAR(50),
    crime_type VARCHAR(100) NOT NULL,
    sentence_start DATE NOT NULL,
    sentence_end DATE,
    sentence_duration_months INT,
    cell_id INT,
    status ENUM('active', 'released', 'transferred', 'deceased') DEFAULT 'active',
    photo VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (cell_id) REFERENCES cells(id) ON DELETE SET NULL
);

-- Visitors table
CREATE TABLE IF NOT EXISTS visitors (
    id INT PRIMARY KEY AUTO_INCREMENT,
    visitor_id VARCHAR(20) UNIQUE NOT NULL,
    first_name VARCHAR(50) NOT NULL,
    last_name VARCHAR(50) NOT NULL,
    phone VARCHAR(20),
    email VARCHAR(100),
    relationship VARCHAR(50),
    id_proof VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Visits table
CREATE TABLE IF NOT EXISTS visits (
    id INT PRIMARY KEY AUTO_INCREMENT,
    inmate_id INT NOT NULL,
    visitor_id INT NOT NULL,
    visit_date DATE NOT NULL,
    visit_time TIME NOT NULL,
    duration_minutes INT DEFAULT 30,
    visit_type ENUM('regular', 'legal', 'medical') DEFAULT 'regular',
    status ENUM('scheduled', 'completed', 'cancelled', 'no_show') DEFAULT 'scheduled',
    notes TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (inmate_id) REFERENCES inmates(id) ON DELETE CASCADE,
    FOREIGN KEY (visitor_id) REFERENCES visitors(id) ON DELETE CASCADE
);

-- Activities/Programs table
CREATE TABLE IF NOT EXISTS activities (
    id INT PRIMARY KEY AUTO_INCREMENT,
    activity_name VARCHAR(100) NOT NULL,
    description TEXT,
    activity_type ENUM('education', 'recreation', 'work', 'therapy', 'religious') NOT NULL,
    schedule_time TIME,
    schedule_days VARCHAR(50),
    capacity INT,
    instructor_id INT,
    status ENUM('active', 'inactive') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (instructor_id) REFERENCES staff(id) ON DELETE SET NULL
);

-- Inmate Activities participation
CREATE TABLE IF NOT EXISTS inmate_activities (
    id INT PRIMARY KEY AUTO_INCREMENT,
    inmate_id INT NOT NULL,
    activity_id INT NOT NULL,
    enrollment_date DATE NOT NULL,
    status ENUM('enrolled', 'completed', 'dropped') DEFAULT 'enrolled',
    FOREIGN KEY (inmate_id) REFERENCES inmates(id) ON DELETE CASCADE,
    FOREIGN KEY (activity_id) REFERENCES activities(id) ON DELETE CASCADE
);

-- Medical Records table
CREATE TABLE IF NOT EXISTS medical_records (
    id INT PRIMARY KEY AUTO_INCREMENT,
    inmate_id INT NOT NULL,
    record_date DATE NOT NULL,
    `condition` VARCHAR(200),
    diagnosis TEXT,
    treatment TEXT,
    medication VARCHAR(255),
    doctor_name VARCHAR(100),
    notes TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (inmate_id) REFERENCES inmates(id) ON DELETE CASCADE
);

-- Incidents table
CREATE TABLE IF NOT EXISTS incidents (
    id INT PRIMARY KEY AUTO_INCREMENT,
    incident_id VARCHAR(20) UNIQUE NOT NULL,
    inmate_id INT,
    staff_id INT,
    incident_type ENUM('violence', 'escape_attempt', 'contraband', 'discipline', 'medical', 'other') NOT NULL,
    incident_date DATETIME NOT NULL,
    description TEXT NOT NULL,
    severity ENUM('low', 'medium', 'high', 'critical') DEFAULT 'medium',
    status ENUM('reported', 'under_investigation', 'resolved', 'closed') DEFAULT 'reported',
    reported_by INT,
    resolution TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (inmate_id) REFERENCES inmates(id) ON DELETE SET NULL,
    FOREIGN KEY (staff_id) REFERENCES staff(id) ON DELETE SET NULL,
    FOREIGN KEY (reported_by) REFERENCES users(id) ON DELETE SET NULL
);

-- Insert default admin user (password: admin123)
INSERT INTO users (username, email, password, role, full_name) VALUES
('admin', 'admin@prison.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin', 'System Administrator');

-- Insert user carine (password: 123)
-- Note: For password "123", use the PHP script add_user_carine.php instead
-- Or generate a new hash using: password_hash("123", PASSWORD_DEFAULT)

-- Insert sample data
INSERT INTO cells (cell_number, block_name, capacity, cell_type) VALUES
('A-101', 'Block A', 2, 'shared'),
('A-102', 'Block A', 2, 'shared'),
('B-201', 'Block B', 1, 'single'),
('B-202', 'Block B', 1, 'single'),
('C-301', 'Block C', 4, 'shared');

