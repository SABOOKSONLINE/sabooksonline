CREATE TABLE audiobook_chapters (
    id INT AUTO_INCREMENT PRIMARY KEY,
    audiobook_id INT NOT NULL,
    chapter_number INT NOT NULL,
    title VARCHAR(255),
    audio_url VARCHAR(255) NOT NULL,
    duration_minutes INT,
    FOREIGN KEY (audiobook_id) REFERENCES audiobooks(id) ON DELETE CASCADE
);
