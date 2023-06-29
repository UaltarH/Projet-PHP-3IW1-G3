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
-- DROP TABLE IF EXISTS carte_chance_page CASCADE;
DROP TABLE IF EXISTS carte_chance_content CASCADE;
DROP TABLE IF EXISTS carte_chance_category_jeux CASCADE;
DROP TABLE IF EXISTS carte_chance_jeux CASCADE;

-- Ajout de la librairie UUID
CREATE
EXTENSION IF NOT EXISTS "uuid-ossp";

-- Création des tables (sans les tables de jointure)
CREATE TABLE carte_chance_permission
(
    id              SERIAL PRIMARY KEY,
    permission_name VARCHAR(64) NOT NULL
);

CREATE TABLE carte_chance_role
(
    id        SERIAL PRIMARY KEY,
    role_name VARCHAR(64) NOT NULL
);

CREATE TABLE carte_chance_user
(
    id                 UUID DEFAULT uuid_generate_v4() UNIQUE,
    pseudo             VARCHAR(15) NOT NULL UNIQUE,
    first_name         VARCHAR(64) NOT NULL,
    last_name          VARCHAR(64) NOT NULL,
    email              VARCHAR(64) NOT NULL UNIQUE,
    password           VARCHAR(64) NOT NULL,
    email_confirmation BOOLEAN     NOT NULL,
    confirmToken       VARCHAR(255),
    phone_number       INTEGER     NOT NULL UNIQUE,
    date_inscription   DATE        NOT NULL,
    role_id            SERIAL      NOT NULL,
    FOREIGN KEY (role_id) REFERENCES carte_chance_role (id)
);

CREATE TABLE carte_chance_comment
(
    id            UUID DEFAULT uuid_generate_v4() UNIQUE,
    content       TEXT NOT NULL,
    creation_date DATE NOT NULL,
    user_id       UUID,
    PRIMARY KEY (id),
    FOREIGN KEY (user_id) REFERENCES carte_chance_user (id)
);

CREATE TABLE carte_chance_category_article
(
    id            UUID DEFAULT uuid_generate_v4() UNIQUE,
    category_name VARCHAR(64)  NOT NULL UNIQUE,
    description   VARCHAR(128) NOT NULL
);

CREATE TABLE carte_chance_article
(
    id           UUID DEFAULT uuid_generate_v4() UNIQUE,
    title        VARCHAR(64) NOT NULL UNIQUE,
    content      TEXT        NOT NULL,
    created_date DATE        NOT NULL,
    updated_date DATE        NOT NULL,
    category_id  UUID,
    PRIMARY KEY (id),
    FOREIGN KEY (category_id) REFERENCES carte_chance_category_article (id)
);

-- CREATE TABLE carte_chance_page (
--     id UUID DEFAULT uuid_generate_v4(),
--     title VARCHAR(8) NOT NULL,
--     creation_date DATE NOT NULL,
--     article_id SERIAL,
--     PRIMARY KEY (id),
--     FOREIGN KEY (article_id) REFERENCES carte_chance_article (id)
-- );

CREATE TABLE carte_chance_content
(
    id           UUID DEFAULT uuid_generate_v4() UNIQUE,
    path_content VARCHAR(255) NOT NULL,
    PRIMARY KEY (id)
);

CREATE TABLE carte_chance_category_jeux
(
    id            UUID DEFAULT uuid_generate_v4() UNIQUE,
    category_name VARCHAR(64)  NOT NULL UNIQUE,
    description   VARCHAR(128) NOT NULL
);

CREATE TABLE carte_chance_jeux
(
    id          UUID DEFAULT uuid_generate_v4() UNIQUE,
    title       VARCHAR(64) NOT NULL UNIQUE,
    category_id UUID,
    PRIMARY KEY (id),
    FOREIGN KEY (category_id) REFERENCES carte_chance_category_jeux (id)
);

-- Création des tables de jointure
CREATE TABLE carte_chance_role_permission
(
    permission_id SERIAL,
    role_id       SERIAL,
    PRIMARY KEY (permission_id, role_id),
    FOREIGN KEY (permission_id) REFERENCES carte_chance_permission (id),
    FOREIGN KEY (role_id) REFERENCES carte_chance_role (id)
);

CREATE TABLE carte_chance_comment_article
(
    article_id UUID,
    comment_id UUID,
    PRIMARY KEY (article_id, comment_id),
    FOREIGN KEY (article_id) REFERENCES carte_chance_article (id),
    FOREIGN KEY (comment_id) REFERENCES carte_chance_comment (id)
);

CREATE TABLE carte_chance_article_jeux
(
    article_id UUID,
    jeux_id    UUID,
    PRIMARY KEY (article_id, jeux_id),
    FOREIGN KEY (article_id) REFERENCES carte_chance_article (id),
    FOREIGN KEY (jeux_id) REFERENCES carte_chance_jeux (id)
);

CREATE TABLE carte_chance_article_content
(
    article_id UUID,
    content_id UUID,
    PRIMARY KEY (article_id, content_id),
    FOREIGN KEY (article_id) REFERENCES carte_chance_article (id),
    FOREIGN KEY (content_id) REFERENCES carte_chance_content (id)
);

CREATE TABLE carte_chance_jeux_content
(
    jeux_id    UUID,
    content_id UUID,
    PRIMARY KEY (jeux_id, content_id),
    FOREIGN KEY (jeux_id) REFERENCES carte_chance_jeux (id),
    FOREIGN KEY (content_id) REFERENCES carte_chance_content (id)
);

------------------------------------------------------------------------------
------------------------------- INSERTIONS -----------------------------------
------------------------------------------------------------------------------

-- carte_chance_permission
INSERT INTO carte_chance_permission (id, permission_name)
VALUES (DEFAULT, 'Create'),
       (DEFAULT, 'Read'),
       (DEFAULT, 'Update'),
       (DEFAULT, 'Delete');

-- carte_chance_role
INSERT INTO carte_chance_role (id, role_name)
VALUES (DEFAULT, 'user'),
       (DEFAULT, 'admin'),
       (DEFAULT, 'moderator');

-- carte_chance_role_permission
INSERT INTO carte_chance_role_permission (permission_id, role_id)
VALUES (1, 1),
       (2, 1),
       (3, 1),
       (4, 1),
       (2, 2);

-- carte_chance_user
INSERT INTO carte_chance_user (id, pseudo, first_name, last_name, email, password, email_confirmation, confirmToken,
                               phone_number, date_inscription, role_id)
VALUES (DEFAULT, 'user_pseudo', 'Mathieu', 'Pannetrat', 'mathieu@gmail.com', 'Azerty123', TRUE, NULL, 600000001,
        '2023-06-03', 1),
       (DEFAULT, 'admin_pseudo', 'MathieuAdmin', 'PannetratAdmin', 'mathieuAdmin@gmail.com', 'Azerty123', TRUE, NULL,
        60000000, '2023-06-03', 2);

-- carte_chance_category_article
INSERT INTO carte_chance_category_article (id, category_name, description)
VALUES (DEFAULT, 'Jeux', 'Cette catégorie regroupe tous les articles qui présentent un jeu'),
       (DEFAULT, 'Trucs et astuces',
        'Cette catégorie regroupe tous les articles qui font référence à un jeu en particulier');

-- carte_chance_category_jeux
INSERT INTO carte_chance_category_jeux (id, category_name, description)
VALUES (DEFAULT, 'Jeux de cartes', 'Cette catégorie regroupe tous les jeux de cartes'),
       (DEFAULT, 'Jeux de dés', 'Cette catégorie regroupe tous les jeux de dés'),
       (DEFAULT, 'Jeux de plateau', 'Cette catégorie regroupe tous les jeux de plateau');

-- carte_chance_jeux
DO
$$
DECLARE
uuid_categorie uuid;
BEGIN
SELECT id
INTO uuid_categorie
FROM carte_chance_category_jeux
WHERE category_name = 'Jeux de cartes';

INSERT INTO carte_chance_jeux (id, title, category_id)
VALUES (DEFAULT, 'Poker', uuid_categorie),
       (DEFAULT, 'Belote', uuid_categorie),
       (DEFAULT, 'Uno', uuid_categorie);
END $$;

DO
$$
DECLARE
uuid_categorie uuid;
BEGIN
SELECT id
INTO uuid_categorie
FROM carte_chance_category_jeux
WHERE category_name = 'Jeux de dés';

INSERT INTO carte_chance_jeux (id, title, category_id)
VALUES (DEFAULT, 'Yams', uuid_categorie),
       (DEFAULT, '421', uuid_categorie);
END $$;

DO
$$
DECLARE
uuid_categorie uuid;
BEGIN
SELECT id
INTO uuid_categorie
FROM carte_chance_category_jeux
WHERE category_name = 'Jeux de plateau';

INSERT INTO carte_chance_jeux (id, title, category_id)
VALUES (DEFAULT, 'Monopoly', uuid_categorie),
       (DEFAULT, 'Jungle Speed', uuid_categorie);
END $$;
