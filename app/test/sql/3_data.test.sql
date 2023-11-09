USE library_test;

SET FOREIGN_KEY_CHECKS = 0;

TRUNCATE TABLE users;
TRUNCATE TABLE authors;
TRUNCATE TABLE books;

SET FOREIGN_KEY_CHECKS = 1;

INSERT INTO users(id, email, password, type, is_active, token) VALUES
(1, 'rlozahic@cogelec.fr', 'password','user', true, 'token'),
(2, 'jhyiete@cogelec.fr', 'password','user', true, 'token');


INSERT INTO authors(id, first_name, last_name, owner_id) VALUES
(1, 'Toto', 'Blue', 1),
(2, 'Jojo', 'Red', 2);

INSERT INTO books(id, name, author_id, owner_id) VALUES
(1, 'livre 1', 1, 1),
(2, 'livre 2', 2, 2);