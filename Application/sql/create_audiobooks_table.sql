CREATE TABLE audiobooks (
    id INT AUTO_INCREMENT PRIMARY KEY,
    audiobook_id INT NOT NULL,
    narrator VARCHAR(255),
    duration_minutes INT,
    release_date DATE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (audiobook_id) REFERENCES posts(ID) ON DELETE CASCADE
);