-- -----------------------------------------------------------------------------
-- init.sql
--  Run this script before any use of LPIC bot
-- 
-- @author: Jérémie Balagna-Ranin <jeremie.balagna@autistici.org>
-- -----------------------------------------------------------------------------

-- -----------------------------------------------------------------------------
-- CREATE clauses
-- -----------------------------------------------------------------------------

-- Store user infos
DROP TABLE IF EXISTS user;
CREATE  TABLE IF NOT EXISTS user
  (u_id INTEGER PRIMARY KEY AUTOINCREMENT ,
  u_pseudo  VARCHAR(255) NOT NULL ,
  u_start   DATETIME NOT NULL ,
  u_score   INTEGER );

-- Store questions
DROP TABLE IF EXISTS question;
CREATE  TABLE IF NOT EXISTS question
  (q_id       INTEGER PRIMARY KEY AUTOINCREMENT ,
  q_txt       TEXT NULL ,
  q_lvl       INTEGER NULL ,
  q_lang      INTEGER NULL);

-- Store answers (if true: a_is_right = 1 else: a_is_right = 0)
DROP TABLE IF EXISTS answer;
CREATE TABLE IF NOT EXISTS answer
  (a_id       INTEGER PRIMARY KEY AUTOINCREMENT ,
  a_q_id      INTEGER ,
  a_is_right  INTEGER ,
  a_txt       TEXT NULL);

-- Store LPI levels (101, 102, ...)
DROP TABLE IF EXISTS level;
CREATE TABLE IF NOT EXISTS level
  (le_id  INTEGER PRIMARY KEY AUTOINCREMENT ,
  le_name INT NULL);

-- Store question language
DROP TABLE IF EXISTS lang;
CREATE TABLE IF NOT EXISTS lang
  (la_id    INTEGER PRIMARY KEY AUTOINCREMENT ,
  la_short  VARCHAR(2) NULL);

-- -----------------------------------------------------------------------------
-- INSERT clauses
-- -----------------------------------------------------------------------------

INSERT INTO level (le_name) VALUES (101), (102), (201), (202), (301), (302), (303);
INSERT INTO lang (la_short) VALUES ('fr'), ('en');
