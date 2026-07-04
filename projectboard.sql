CREATE DATABASE IF NOT EXISTS projectboard
CHARACTER SET utf8mb4
COLLATE utf8mb4_hungarian_ci;

USE projectboard;

CREATE TABLE admins (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_hungarian_ci;

CREATE TABLE projects (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    description TEXT,
    type ENUM('personal', 'client') NOT NULL DEFAULT 'personal',
    status ENUM('active', 'completed', 'paused') NOT NULL DEFAULT 'active',
    price INT DEFAULT NULL,
    deadline DATE DEFAULT NULL,
    github_url VARCHAR(255) DEFAULT NULL,
    live_url VARCHAR(255) DEFAULT NULL,
    notes TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_hungarian_ci;

CREATE TABLE project_versions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    project_id INT NOT NULL,
    version_number VARCHAR(50) NOT NULL,
    change_type VARCHAR(100) DEFAULT NULL,
    description TEXT,
    deployed TINYINT(1) DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_project_versions_project
        FOREIGN KEY (project_id)
        REFERENCES projects(id)
        ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_hungarian_ci;

INSERT INTO admins (username, password)
VALUES ('admin', 'admin13');