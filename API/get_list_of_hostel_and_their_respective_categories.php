CREATE TABLE IF NOT EXISTS hostel_with_categories (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    constant_table_name VARCHAR(255) NOT NULL,
    variable_table_name VARCHAR(255) NOT NULL,
    hostel_name VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    is_locked BOOLEAN DEFAULT FALSE
)