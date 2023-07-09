CREATE DATABASE learning;

USE learning;

DROP TABLE IF EXISTS event;
DROP TABLE IF EXISTS place;
DROP TABLE IF EXISTS comment;
DROP TABLE IF EXISTS post;
DROP TABLE IF EXISTS user;
DROP TABLE IF EXISTS event;
DROP TABLE IF EXISTS place;


CREATE TABLE user
(

    id       INT PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(25) UNIQUE NOT NULL,
    password VARCHAR(25)        NOT NULL,
    picture  BOOLEAN DEFAULT FALSE,
    email    VARCHAR(25) UNIQUE NOT NULL


);

CREATE TABLE post
(
    id      INT PRIMARY KEY AUTO_INCREMENT,
    userid  INT REFERENCES user (id),
    content VARCHAR(350),
    date    DATETIME DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE comment
(

    id      INT PRIMARY KEY AUTO_INCREMENT,
    userid  INT REFERENCES user (id),
    postid  INT REFERENCES post (id),
    content VARCHAR(255),
    date    DATETIME DEFAULT CURRENT_TIMESTAMP

);


CREATE TABLE place
(

    id          INT PRIMARY KEY AUTO_INCREMENT,
    name        VARCHAR(60) UNIQUE NOT NULL,
    description VARCHAR(1000)      NOT NULL,
    latitude    DOUBLE(20, 10)     NOT NULL,
    longitude   DOUBLE(20, 10)     NOT NULL,
    userid      INT REFERENCES user (id),
    createdAt   DATETIME DEFAULT CURRENT_TIMESTAMP

);


CREATE TABLE event
(

    id          INT PRIMARY KEY AUTO_INCREMENT,
    name        VARCHAR(60) UNIQUE NOT NULL,
    description VARCHAR(1000)      NOT NULL,
    userid      INT REFERENCES user (id),
    # an event takes (place):
    placeid     INT REFERENCES place (id),
    createdAt   DATETIME DEFAULT CURRENT_TIMESTAMP,
    date        DATETIME DEFAULT CURRENT_TIMESTAMP

);