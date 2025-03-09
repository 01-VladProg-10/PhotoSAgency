CREATE TABLE models (
    id INT AUTO_INCREMENT PRIMARY KEY,
    hobbies TEXT,
    age INT,
    height VARCHAR(10),
    dimensions VARCHAR(50),
    eye_color VARCHAR(50),
    hair_color VARCHAR(50),
    experience TEXT,
    phone_number VARCHAR(20) UNIQUE,
    instagram VARCHAR(100),
    user_id INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

CREATE TABLE model_photos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    model_id INT,
    photo_path VARCHAR(2048),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (model_id) REFERENCES models(id) ON DELETE CASCADE
);

CREATE TABLE videos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    model_id INT,
    file_path VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (model_id) REFERENCES models(id) ON DELETE CASCADE
);

CREATE TABLE sessions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    available_date_id INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

CREATE TABLE available_dates (
    id INT AUTO_INCREMENT PRIMARY KEY,
    date VARCHAR(16) UNIQUE,
    is_booked BOOLEAN DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

ALTER TABLE sessions
ADD CONSTRAINT fk_available_date
FOREIGN KEY (available_date_id) REFERENCES available_dates(id)
ON DELETE CASCADE;
