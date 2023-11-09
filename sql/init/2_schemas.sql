USE library;

CREATE TABLE IF NOT EXISTS users (
                       id INTEGER UNSIGNED PRIMARY KEY AUTO_INCREMENT NOT NULL,
                       email VARCHAR(255) UNIQUE NOT NULL,
                       password VARCHAR(255) NOT NULL,
                       type VARCHAR(127) DEFAULT 'user',
                       is_active bool DEFAULT false,
                       token varchar(255) NOT NULL
) ENGINE=INNODB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

CREATE TABLE IF NOT EXISTS authors  (
                         id INTEGER UNSIGNED PRIMARY KEY AUTO_INCREMENT NOT NULL,
                         first_name VARCHAR(63) NOT NULL,
                         last_name VARCHAR(63) NOT NULL,
                         owner_id INTEGER UNSIGNED,
                         FOREIGN KEY (owner_id) REFERENCES users(id)
) ENGINE=INNODB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

CREATE TABLE IF NOT EXISTS books (
                       id INTEGER UNSIGNED PRIMARY KEY AUTO_INCREMENT NOT NULL,
                       name VARCHAR(127) NOT NULL,
                       author_id INTEGER UNSIGNED,
                       owner_id INTEGER UNSIGNED,
                       FOREIGN KEY (author_id) REFERENCES authors(id),
                       FOREIGN KEY (owner_id) REFERENCES users(id)
) ENGINE=INNODB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;