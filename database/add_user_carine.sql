-- Add user: carine with password: 123
-- Run this SQL in phpMyAdmin after importing the main schema
-- OR use the PHP script: add_user_carine.php (easier method)

USE prison_management;

-- Insert user carine (password: 123)
-- Note: This hash is for password "123"
INSERT INTO users (username, email, password, role, full_name) VALUES
('carine', 'carine@prison.com', '$2y$10$N9qo8uLOickgx2ZMRZoMyeIjZAgcfl7p92ldGxad68LJZdL17lhWy', 'admin', 'Carine');

-- If the above doesn't work, use the PHP script method instead:
-- 1. Go to: http://localhost/BEST/add_user_carine.php
-- 2. It will automatically create the user with correct password hash

