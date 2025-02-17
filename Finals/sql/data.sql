CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL,
    password VARCHAR(255) NOT NULL,
    role ENUM('applicant', 'hr') NOT NULL
);

CREATE TABLE job_posts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    description TEXT NOT NULL,
    created_by INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (created_by) REFERENCES users(id)
);

CREATE TABLE applications (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL, 
    job_post_id INT NOT NULL, 
    cover_message TEXT NOT NULL,
    resume VARCHAR(255) NOT NULL, 
    resume_path VARCHAR(255) NOT NULL,
    status ENUM('pending', 'accepted', 'rejected') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (job_post_id) REFERENCES job_posts(id)
);


CREATE TABLE messages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    from_user_id INT NOT NULL, 
    to_user_id INT NOT NULL, 
    message TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (from_user_id) REFERENCES users(id),
    FOREIGN KEY (to_user_id) REFERENCES users(id)
);

ALTER TABLE applications DROP FOREIGN KEY applications_ibfk_2; ALTER TABLE applications ADD CONSTRAINT applications_ibfk_2 FOREIGN KEY (job_post_id) REFERENCES job_posts (id) ON DELETE CASCADE;