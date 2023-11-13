USE library;

INSERT INTO users(id, email, password, type, is_active, token) VALUES
(1, 'fake-address@fake.fr', 'password','user', true, 'token');

INSERT INTO authors(first_name, last_name, owner_id) VALUES
('Toto', 'Blue', 1),
('Jojo', 'Red', 1),
('Mama', 'Green', 1);