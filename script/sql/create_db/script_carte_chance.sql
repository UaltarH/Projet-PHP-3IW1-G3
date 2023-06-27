-- Suppression des tables existantes
DROP TABLE IF EXISTS carte_chance_role_permission CASCADE;
DROP TABLE IF EXISTS carte_chance_comment_article CASCADE;
DROP TABLE IF EXISTS carte_chance_article_jeux CASCADE;
DROP TABLE IF EXISTS carte_chance_article_content CASCADE;
DROP TABLE IF EXISTS carte_chance_jeux_content CASCADE;
DROP TABLE IF EXISTS carte_chance_permission CASCADE;
DROP TABLE IF EXISTS carte_chance_role CASCADE;
DROP TABLE IF EXISTS carte_chance_user CASCADE;
DROP TABLE IF EXISTS carte_chance_comment CASCADE;
DROP TABLE IF EXISTS carte_chance_category_article CASCADE;
DROP TABLE IF EXISTS carte_chance_article CASCADE;
DROP TABLE IF EXISTS carte_chance_page CASCADE;
DROP TABLE IF EXISTS carte_chance_content CASCADE;
DROP TABLE IF EXISTS carte_chance_category_jeux CASCADE;
DROP TABLE IF EXISTS carte_chance_jeux CASCADE;

-- Ajout de la librairie UUID
CREATE EXTENSION IF NOT EXISTS "uuid-ossp";

-- Création des tables (sans les tables de jointure)
CREATE TABLE carte_chance_permission (
    id SERIAL,
    permission_name VARCHAR(64) NOT NULL,
    PRIMARY KEY (id)
);

CREATE TABLE carte_chance_role (
    id SERIAL,
    role_name VARCHAR(64) NOT NULL,
    PRIMARY KEY (id)
);

CREATE TABLE carte_chance_user (
    id UUID DEFAULT uuid_generate_v4(),
    pseudo VARCHAR(15) NOT NULL,
    first_name VARCHAR(64) NOT NULL,
    last_name VARCHAR(64) NOT NULL,
    email VARCHAR(64) NOT NULL,
    password VARCHAR(64) NOT NULL,
    email_confirmation BOOLEAN NOT NULL,
    confirmToken VARCHAR(255) NULL,
    phone_number INTEGER NOT NULL,
    date_inscription DATE NOT NULL,
    role_id SERIAL NOT NULL,
    PRIMARY KEY (id),
    FOREIGN KEY (role_id) REFERENCES carte_chance_role (id)
);

CREATE TABLE carte_chance_comment (
    id UUID DEFAULT uuid_generate_v4(),
    content TEXT NOT NULL,
    creation_date DATE NOT NULL,
    user_id UUID DEFAULT uuid_generate_v4(),
    PRIMARY KEY (id),
    FOREIGN KEY (user_id) REFERENCES carte_chance_user (id)
);

CREATE TABLE carte_chance_category_article (
    id UUID DEFAULT uuid_generate_v4(),
    category_name VARCHAR(64) NOT NULL,
    description VARCHAR(128) NOT NULL,
    PRIMARY KEY (id)
);

CREATE TABLE carte_chance_article (
    id UUID DEFAULT uuid_generate_v4(),
    content TEXT NOT NULL,
    created_date DATE NOT NULL,
    updated_date DATE NOT NULL,
    category_id UUID DEFAULT uuid_generate_v4(),
    PRIMARY KEY (id),
    FOREIGN KEY (category_id) REFERENCES carte_chance_category_article (id)
);

CREATE TABLE carte_chance_page (
    id UUID DEFAULT uuid_generate_v4(),
    title VARCHAR(8) NOT NULL,
    creation_date DATE NOT NULL,
    article_id UUID DEFAULT uuid_generate_v4(),
    PRIMARY KEY (id),
    FOREIGN KEY (article_id) REFERENCES carte_chance_article (id)
);

CREATE TABLE carte_chance_content (
    id UUID DEFAULT uuid_generate_v4(),
    path_content VARCHAR(64) NOT NULL,
    PRIMARY KEY (id)
);

CREATE TABLE carte_chance_category_jeux (
    id UUID DEFAULT uuid_generate_v4(),
    category_name VARCHAR(64) NOT NULL,
    description VARCHAR(128) NOT NULL,
    PRIMARY KEY (id)
);

CREATE TABLE carte_chance_jeux (
    id UUID DEFAULT uuid_generate_v4(),
    title VARCHAR(64) NOT NULL,
    category_id UUID DEFAULT uuid_generate_v4() NOT NULL,
    PRIMARY KEY (id),
    FOREIGN KEY (category_id) REFERENCES carte_chance_category_jeux (id)
);

-- Création des tables de jointure
CREATE TABLE carte_chance_role_permission (
    permission_id SERIAL,
    role_id SERIAL,
    PRIMARY KEY (permission_id, role_id),
    FOREIGN KEY (permission_id) REFERENCES carte_chance_permission (id),
    FOREIGN KEY (role_id) REFERENCES carte_chance_role (id)
);

CREATE TABLE carte_chance_comment_article (
    article_id UUID DEFAULT uuid_generate_v4(),
    comment_id UUID DEFAULT uuid_generate_v4(),
    PRIMARY KEY (article_id, comment_id),
    FOREIGN KEY (article_id) REFERENCES carte_chance_article (id),
    FOREIGN KEY (comment_id) REFERENCES carte_chance_comment (id)
);

CREATE TABLE carte_chance_article_jeux (
    article_id UUID DEFAULT uuid_generate_v4(),
    jeux_id UUID DEFAULT uuid_generate_v4(),
    PRIMARY KEY (article_id, jeux_id),
    FOREIGN KEY (article_id) REFERENCES carte_chance_article (id),
    FOREIGN KEY (jeux_id) REFERENCES carte_chance_jeux (id)
);

CREATE TABLE carte_chance_article_content (
    article_id UUID DEFAULT uuid_generate_v4(),
    content_id UUID DEFAULT uuid_generate_v4(),
    PRIMARY KEY (article_id, content_id),
    FOREIGN KEY (article_id) REFERENCES carte_chance_article (id),
    FOREIGN KEY (content_id) REFERENCES carte_chance_content (id)
);

CREATE TABLE carte_chance_jeux_content (
    jeux_id UUID DEFAULT uuid_generate_v4(),
    content_id UUID DEFAULT uuid_generate_v4(),
    PRIMARY KEY (jeux_id, content_id),
    FOREIGN KEY (jeux_id) REFERENCES carte_chance_jeux (id),
    FOREIGN KEY (content_id) REFERENCES carte_chance_content (id)
);

------------------------------------------------------------------------------
------------------------------- INSERTIONS -----------------------------------
------------------------------------------------------------------------------

-- carte_chance_permission
INSERT INTO carte_chance_permission VALUES
    (DEFAULT, 'Create'),
    (DEFAULT, 'Read'),
    (DEFAULT, 'Update'),
    (DEFAULT, 'Delete');

-- carte_chance_role
INSERT INTO carte_chance_role VALUES
    (DEFAULT, 'user'),
    (DEFAULT, 'admin');

-- carte_chance_role_permission
INSERT INTO carte_chance_role_permission VALUES
    (1, 1),
    (2, 1),
    (3, 1),
    (4, 1),
    (2, 2);

-- carte_chance_user
INSERT INTO carte_chance_user VALUES
    (DEFAULT, 'user_pseudo', 'Mathieu', 'Pannetrat', 'mathieu@gmail.com', 'azerty123', TRUE, NULL, 600000000, '2023-06-03', 1),
    (DEFAULT, 'admin_pseudo', 'MathieuAdmin', 'PannetratAdmin', 'mathieuAdmin@gmail.com', 'azerty123', TRUE, NULL, 600000000, '2023-06-03', 2);
