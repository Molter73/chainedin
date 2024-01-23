CREATE TABLE users (
    id INT UNSIGNED NOT NULL AUTO_INCREMENT,
    name TEXT NOT NULL,
    email TEXT NOT NULL UNIQUE,
    pass TEXT NOT NULL,
    PRIMARY KEY(id)
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
        ON UPDATE RESTRICT
);
