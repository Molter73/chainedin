CREATE TABLE users (
    id INT UNSIGNED NOT NULL AUTO_INCREMENT,
    email TEXT NOT NULL UNIQUE,
    pass TEXT NOT NULL,
    PRIMARY KEY(id)
);

CREATE TABLE profiles (
    id INT UNSIGNED NOT NULL,
    name TEXT,
    surname TEXT,
    phone TEXT,
    picture TEXT,
    CV TEXT,
    PRIMARY KEY(id),
    CONSTRAINT `fk_profile_user`
        FOREIGN KEY (id) REFERENCES users (id)
        ON DELETE CASCADE
        ON UPDATE RESTRICT
);

CREATE TABLE jobs (
    id INT UNSIGNED NOT NULL AUTO_INCREMENT,
    title TEXT NOT NULL,
    description LONGTEXT NOT NULL,
    company TEXT NOT NULL,
    logo TEXT,
    creation_date DATE,
    PRIMARY KEY(id)
);

CREATE TABLE applicants(
    user_id INT UNSIGNED NOT NULL,
    job_id INT UNSIGNED NOT NULL,
    CONSTRAINT `fk_applicant_user`
        FOREIGN KEY (user_id) REFERENCES users (id)
        ON DELETE CASCADE
        ON UPDATE RESTRICT,
    CONSTRAINT `fk_applicant_job`
        FOREIGN KEY (job_id) REFERENCES jobs (id)
        ON DELETE CASCADE
        ON UPDATE RESTRICT,
    UNIQUE KEY `unique_applicants` (user_id, job_id)
);
