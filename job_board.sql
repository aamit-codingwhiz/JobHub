CREATE DATABASE job_board;

USE job_board;

CREATE TABLE Users (
    user_id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('company', 'job_seeker') NOT NULL
);

CREATE TABLE CompanyProfiles (
    company_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT UNIQUE,
    company_name VARCHAR(255) NOT NULL,
    logo_path VARCHAR(255),

    FOREIGN KEY (user_id) REFERENCES Users(user_id)
);

CREATE TABLE Jobs (
    job_id INT AUTO_INCREMENT PRIMARY KEY,
    company_id INT,
    title VARCHAR(255) NOT NULL,
    description TEXT NOT NULL,
    location VARCHAR(255) NOT NULL,
    application_deadline DATE NOT NULL,

    FOREIGN KEY (company_id) REFERENCES CompanyProfiles(company_id)
);

CREATE TABLE Applications (
    application_id INT AUTO_INCREMENT PRIMARY KEY,
    job_id INT,
    user_id INT,
    Name VARCHAR(255),
    Email VARCHAR(255),
    resume_path VARCHAR(255) NOT NULL,

    FOREIGN KEY (job_id) REFERENCES Jobs(job_id),
    FOREIGN KEY (user_id) REFERENCES Users(user_id)
);

