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
    id              UUID DEFAULT uuid_generate_v4(),
    permission_name VARCHAR(64) NOT NULL,
    PRIMARY KEY (id)
);

CREATE TABLE carte_chance_role
(
    id        UUID DEFAULT uuid_generate_v4(),
    role_name VARCHAR(64) NOT NULL,
    PRIMARY KEY (id)
);

CREATE TABLE carte_chance_user
(
    id                 UUID DEFAULT uuid_generate_v4(),
    pseudo             VARCHAR(15) NOT NULL UNIQUE,
    first_name         VARCHAR(64) NOT NULL,
    last_name          VARCHAR(64) NOT NULL,
    email              VARCHAR(64) NOT NULL UNIQUE,
    password           VARCHAR(64) NOT NULL,
    email_confirmation BOOLEAN     NOT NULL,
    confirmAndResetToken       VARCHAR(255),
    phone_number       INTEGER     NOT NULL UNIQUE,
    date_inscription   DATE        NOT NULL,
    role_id            UUID      NOT NULL,
    PRIMARY KEY (id),
    FOREIGN KEY (role_id) REFERENCES carte_chance_role (id)
);

CREATE TABLE carte_chance_comment
(
    id            UUID DEFAULT uuid_generate_v4(),
    content       TEXT NOT NULL,
    creation_date DATE NOT NULL,
    user_id       UUID,
    PRIMARY KEY (id),
    FOREIGN KEY (user_id) REFERENCES carte_chance_user (id)
);

CREATE TABLE carte_chance_category_article
(
    id            UUID DEFAULT uuid_generate_v4(),
    category_name VARCHAR(64)  NOT NULL UNIQUE,
    description   VARCHAR(128) NOT NULL,
    PRIMARY KEY (id)
);

CREATE TABLE carte_chance_article
(
    id           UUID DEFAULT uuid_generate_v4(),
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
    id           UUID DEFAULT uuid_generate_v4(),
    path_content VARCHAR(255) NOT NULL,
    PRIMARY KEY (id)
);

CREATE TABLE carte_chance_category_jeux
(
    id            UUID DEFAULT uuid_generate_v4(),
    category_name VARCHAR(64)  NOT NULL UNIQUE,
    description   VARCHAR(128) NOT NULL,
    PRIMARY KEY (id)
);

CREATE TABLE carte_chance_jeux
(
    id          UUID DEFAULT uuid_generate_v4(),
    title       VARCHAR(64) NOT NULL UNIQUE,
    category_id UUID,
    PRIMARY KEY (id),
    FOREIGN KEY (category_id) REFERENCES carte_chance_category_jeux (id)
);

-- Création des tables de jointure
CREATE TABLE carte_chance_role_permission
(
    permission_id UUID,
    role_id       UUID,
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
VALUES (uuid_generate_v4(), 'Create'),
       (uuid_generate_v4(), 'Read'),
       (uuid_generate_v4(), 'Update'),
       (uuid_generate_v4(), 'Delete');

-- carte_chance_role
INSERT INTO carte_chance_role (id, role_name)
VALUES (uuid_generate_v4(), 'user'),
       (uuid_generate_v4(), 'admin'),
       (uuid_generate_v4(), 'moderator');

-- carte_chance_role_permission
INSERT INTO carte_chance_role_permission (permission_id, role_id)
VALUES ((SELECT id FROM carte_chance_permission WHERE permission_name = 'Create'),
        (SELECT id FROM carte_chance_role WHERE role_name = 'user')),
       ((SELECT id FROM carte_chance_permission WHERE permission_name = 'Read'),
        (SELECT id FROM carte_chance_role WHERE role_name = 'user')),
       ((SELECT id FROM carte_chance_permission WHERE permission_name = 'Update'),
        (SELECT id FROM carte_chance_role WHERE role_name = 'user')),
       ((SELECT id FROM carte_chance_permission WHERE permission_name = 'Delete'),
        (SELECT id FROM carte_chance_role WHERE role_name = 'user')),
       ((SELECT id FROM carte_chance_permission WHERE permission_name = 'Read'),
        (SELECT id FROM carte_chance_role WHERE role_name = 'admin'));

-- carte_chance_user
INSERT INTO carte_chance_user (id, pseudo, first_name, last_name, email, password, email_confirmation, confirmAndResetToken,
                               phone_number, date_inscription, role_id)
VALUES (uuid_generate_v4(), 'user_pseudo', 'Mathieu', 'Pannetrat', 'mathieu@gmail.com', 'Azerty123', TRUE, NULL, 600000001,
        '2023-06-03', (SELECT id FROM carte_chance_role WHERE role_name = 'user')),
       (uuid_generate_v4(), 'admin_pseudo', 'MathieuAdmin', 'PannetratAdmin', 'mathieuAdmin@gmail.com', 'Azerty123', TRUE, NULL,
        60000000, '2023-06-03', (SELECT id FROM carte_chance_role WHERE role_name = 'admin'));

-- carte_chance_category_article
INSERT INTO carte_chance_category_article (id, category_name, description)
VALUES (uuid_generate_v4(), 'Jeux', 'Cette catégorie regroupe tous les articles qui présentent un jeu'),
       (uuid_generate_v4(), 'Trucs et astuces',
        'Cette catégorie regroupe tous les articles qui font référence à un jeu en particulier');

-- carte_chance_category_jeux
INSERT INTO carte_chance_category_jeux (id, category_name, description)
VALUES (uuid_generate_v4(), 'Jeux de cartes', 'Cette catégorie regroupe tous les jeux de cartes'),
       (uuid_generate_v4(), 'Jeux de dés', 'Cette catégorie regroupe tous les jeux de dés'),
       (uuid_generate_v4(), 'Jeux de plateau', 'Cette catégorie regroupe tous les jeux de plateau');


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
