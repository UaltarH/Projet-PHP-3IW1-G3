-- Suppression des tables existantes
DROP TABLE IF EXISTS $prefix$_role_permission CASCADE;
DROP TABLE IF EXISTS $prefix$_comment_article CASCADE;
DROP TABLE IF EXISTS $prefix$_game_article CASCADE;
DROP TABLE IF EXISTS $prefix$_article_content CASCADE;
DROP TABLE IF EXISTS $prefix$_game_content CASCADE;
DROP TABLE IF EXISTS $prefix$_permission CASCADE;
DROP TABLE IF EXISTS $prefix$_role CASCADE;
DROP TABLE IF EXISTS $prefix$_user CASCADE;
DROP TABLE IF EXISTS $prefix$_comment CASCADE;
DROP TABLE IF EXISTS $prefix$_article_category CASCADE;
DROP TABLE IF EXISTS $prefix$_article CASCADE;
-- DROP TABLE IF EXISTS $prefix$_page CASCADE;
DROP TABLE IF EXISTS $prefix$_content CASCADE;
DROP TABLE IF EXISTS $prefix$_game_category CASCADE;
DROP TABLE IF EXISTS $prefix$_game CASCADE;

-- Ajout de la librairie UUID
CREATE
EXTENSION IF NOT EXISTS "uuid-ossp";

-- Création des tables (sans les tables de jointure)
CREATE TABLE $prefix$_permission
(
    id              UUID DEFAULT uuid_generate_v4(),
    permission_name VARCHAR(64) NOT NULL,
    PRIMARY KEY (id)
);

CREATE TABLE $prefix$_role
(
    id        UUID DEFAULT uuid_generate_v4(),
    role_name VARCHAR(64) NOT NULL,
    PRIMARY KEY (id)
);

CREATE TABLE $prefix$_user
(
    id                 UUID DEFAULT uuid_generate_v4(),
    pseudo             VARCHAR(15) NOT NULL UNIQUE,
    first_name         VARCHAR(64) NOT NULL,
    last_name          VARCHAR(64) NOT NULL,
    email              VARCHAR(64) NOT NULL UNIQUE,
    password           VARCHAR(64) NOT NULL,
    email_confirmation BOOLEAN     NOT NULL,
    confirm_and_reset_token       VARCHAR(255),
    phone_number       INTEGER     NOT NULL UNIQUE,
    date_inscription   DATE        NOT NULL,
    role_id            UUID      NOT NULL,
    PRIMARY KEY (id),
    FOREIGN KEY (role_id) REFERENCES $prefix$_role (id) ON DELETE CASCADE
);

CREATE TABLE $prefix$_comment
(
    id            UUID    DEFAULT uuid_generate_v4() UNIQUE,
    content       TEXT                  NOT NULL,
    creation_date DATE    DEFAULT CURRENT_DATE NOT NULL,
    moderated     BOOLEAN DEFAULT FALSE NOT NULL,
    accepted      BOOLEAN DEFAULT FALSE NOT NULL,
    user_id       UUID,
    PRIMARY KEY (id),
    FOREIGN KEY (user_id) REFERENCES $prefix$_user (id) ON DELETE CASCADE
);

CREATE TABLE $prefix$_article_category
(
    id            UUID DEFAULT uuid_generate_v4(),
    category_name VARCHAR(64)  NOT NULL UNIQUE,
    description   VARCHAR(128) NOT NULL,
    PRIMARY KEY (id)
);

CREATE TABLE $prefix$_article
(
    id           UUID DEFAULT uuid_generate_v4(),
    title        VARCHAR(64) NOT NULL UNIQUE,
    content      TEXT        NOT NULL,
    created_date DATE        NOT NULL,
    updated_date DATE        NOT NULL,
    category_id  UUID,
    PRIMARY KEY (id),
    FOREIGN KEY (category_id) REFERENCES $prefix$_article_category (id) ON DELETE CASCADE
);

CREATE TABLE $prefix$_article_memento
(
    id           UUID DEFAULT uuid_generate_v4(),
    title        VARCHAR(64) NOT NULL,
    content      TEXT        NOT NULL,
    created_date DATE        NOT NULL,
    article_id  UUID,
    PRIMARY KEY (id),
    FOREIGN KEY (article_id) REFERENCES $prefix$_article (id) ON DELETE CASCADE
);

-- CREATE TABLE $prefix$_page (
--     id UUID DEFAULT uuid_generate_v4(),
--     title VARCHAR(8) NOT NULL,
--     creation_date DATE NOT NULL,
--     article_id SERIAL,
--     PRIMARY KEY (id),
--     FOREIGN KEY (article_id) REFERENCES $prefix$_article (id)
-- );

CREATE TABLE $prefix$_content
(
    id           UUID DEFAULT uuid_generate_v4(),
    path_content VARCHAR(255) NOT NULL,
    PRIMARY KEY (id)
);

CREATE TABLE $prefix$_game_category
(
    id            UUID DEFAULT uuid_generate_v4(),
    category_game_name VARCHAR(64)  NOT NULL UNIQUE,
    description   VARCHAR(128) NOT NULL,
    PRIMARY KEY (id)
);

CREATE TABLE $prefix$_game
(
    id          UUID DEFAULT uuid_generate_v4(),
    title_game       VARCHAR(64) NOT NULL UNIQUE,
    category_id UUID,
    PRIMARY KEY (id),
    FOREIGN KEY (category_id) REFERENCES $prefix$_game_category (id) ON DELETE CASCADE
);

-- Création des tables de jointure
CREATE TABLE $prefix$_role_permission
(
    permission_id UUID,
    role_id       UUID,
    PRIMARY KEY (permission_id, role_id),
    FOREIGN KEY (permission_id) REFERENCES $prefix$_permission (id) ON DELETE CASCADE, 
    FOREIGN KEY (role_id) REFERENCES $prefix$_role (id) ON DELETE CASCADE
);

CREATE TABLE $prefix$_comment_article
(
    article_id UUID,
    comment_id UUID,
    PRIMARY KEY (article_id, comment_id),
    FOREIGN KEY (article_id) REFERENCES $prefix$_article (id) ON DELETE CASCADE,
    FOREIGN KEY (comment_id) REFERENCES $prefix$_comment (id) ON DELETE CASCADE
);

CREATE TABLE $prefix$_game_article
(
    article_id UUID,
    jeux_id    UUID,
    PRIMARY KEY (article_id, jeux_id),
    FOREIGN KEY (article_id) REFERENCES $prefix$_article (id) ON DELETE CASCADE,
    FOREIGN KEY (jeux_id) REFERENCES $prefix$_game (id) ON DELETE CASCADE
);

CREATE TABLE $prefix$_article_content
(
    article_id UUID,
    content_id UUID,
    PRIMARY KEY (article_id, content_id),
    FOREIGN KEY (article_id) REFERENCES $prefix$_article (id) ON DELETE CASCADE,
    FOREIGN KEY (content_id) REFERENCES $prefix$_content (id) ON DELETE CASCADE
);

CREATE TABLE $prefix$_game_content
(
    jeux_id    UUID,
    content_id UUID,
    PRIMARY KEY (jeux_id, content_id),
    FOREIGN KEY (jeux_id) REFERENCES $prefix$_game (id) ON DELETE CASCADE,
    FOREIGN KEY (content_id) REFERENCES $prefix$_content (id) ON DELETE CASCADE
);

------------------------------------------------------------------------------
------------------------------- INSERTIONS -----------------------------------
------------------------------------------------------------------------------

-- $prefix$_permission

INSERT INTO $prefix$_permission (id, permission_name)
VALUES (uuid_generate_v4(), 'Create'),
       (uuid_generate_v4(), 'Read'),
       (uuid_generate_v4(), 'Update'),
       (uuid_generate_v4(), 'Delete');

-- $prefix$_role
INSERT INTO $prefix$_role (id, role_name)
VALUES (uuid_generate_v4(), 'user'),
       (uuid_generate_v4(), 'admin'),
       (uuid_generate_v4(), 'moderator');

-- $prefix$_role_permission
INSERT INTO $prefix$_role_permission (permission_id, role_id)
VALUES ((SELECT id FROM $prefix$_permission WHERE permission_name = 'Create'),
        (SELECT id FROM $prefix$_role WHERE role_name = 'user')),
       ((SELECT id FROM $prefix$_permission WHERE permission_name = 'Read'),
        (SELECT id FROM $prefix$_role WHERE role_name = 'user')),
       ((SELECT id FROM $prefix$_permission WHERE permission_name = 'Update'),
        (SELECT id FROM $prefix$_role WHERE role_name = 'user')),
       ((SELECT id FROM $prefix$_permission WHERE permission_name = 'Delete'),
        (SELECT id FROM $prefix$_role WHERE role_name = 'user')),
       ((SELECT id FROM $prefix$_permission WHERE permission_name = 'Read'),
        (SELECT id FROM $prefix$_role WHERE role_name = 'admin'));
