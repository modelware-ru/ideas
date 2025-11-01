CREATE TABLE req (
    id INT UNSIGNED AUTO_INCREMENT NOT NULL,
    method CHAR(5) NOT NULL,
    url VARCHAR(100) NOT NULL,
    query VARCHAR(1000) NOT NULL,
    headers JSON,
    body JSON,
    PRIMARY KEY (id)
) ENGINE = InnoDB;

CREATE TABLE resp (
    id INT UNSIGNED AUTO_INCREMENT NOT NULL,
    req_id INT UNSIGNED NOT NULL,
    data JSON NOT NULL,
    success ENUM ('YES', 'NO') NOT NULL DEFAULT 'YES',
    stutus JSON NOT NULL,
    PRIMARY KEY (id),
    CONSTRAINT resp___req_id FOREIGN KEY (req_id) REFERENCES req(id)
) ENGINE = InnoDB;
