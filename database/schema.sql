CREATE DATABASE IF NOT EXISTS online_exam;
USE online_exam;

DROP TABLE IF EXISTS answers;
DROP TABLE IF EXISTS questions;
DROP TABLE IF EXISTS exams;
DROP TABLE IF EXISTS candidates;
DROP TABLE IF EXISTS admins;

CREATE TABLE admins (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE candidates (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id VARCHAR(50) NOT NULL UNIQUE,
    full_name VARCHAR(120) NOT NULL,
    email VARCHAR(120) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE exams (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(120) NOT NULL,
    duration_minutes INT NOT NULL DEFAULT 30,
    start_time DATETIME NULL,
    end_time DATETIME NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE questions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    exam_id INT NOT NULL,
    question_text TEXT NOT NULL,
    option_a VARCHAR(255) NOT NULL,
    option_b VARCHAR(255) NOT NULL,
    option_c VARCHAR(255) NOT NULL,
    option_d VARCHAR(255) NOT NULL,
    correct_option ENUM('A','B','C','D') NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (exam_id) REFERENCES exams(id) ON DELETE CASCADE
);

CREATE TABLE answers (
    id INT AUTO_INCREMENT PRIMARY KEY,
    candidate_id INT NOT NULL,
    exam_id INT NOT NULL,
    question_id INT NOT NULL,
    selected_option ENUM('A','B','C','D') NULL,
    is_correct TINYINT(1) NULL,
    submitted_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (candidate_id) REFERENCES candidates(id) ON DELETE CASCADE,
    FOREIGN KEY (exam_id) REFERENCES exams(id) ON DELETE CASCADE,
    FOREIGN KEY (question_id) REFERENCES questions(id) ON DELETE CASCADE,
    UNIQUE KEY unique_answer (candidate_id, exam_id, question_id)
);

INSERT INTO admins (username, password)
VALUES ('admin', 'admin123');

INSERT INTO candidates (user_id, full_name, email, password)
VALUES ('CAND001', 'Gourav Candidate', 'candidate@example.com', 'pass123');

INSERT INTO exams (title, duration_minutes, start_time, end_time)
VALUES ('Sample Online Test', 30, NOW(), DATE_ADD(NOW(), INTERVAL 7 DAY));

INSERT INTO questions (exam_id, question_text, option_a, option_b, option_c, option_d, correct_option)
VALUES
(1, 'Which language is primarily used for server-side scripting in this project?', 'Java', 'PHP', 'C#', 'Swift', 'B'),
(1, 'Which database is used here?', 'MongoDB', 'PostgreSQL', 'MySQL', 'SQLite', 'C'),
(1, 'Which tool package is recommended for local PHP development?', 'XAMPP', 'Docker Swarm', 'Kubernetes', 'Jenkins', 'A');
