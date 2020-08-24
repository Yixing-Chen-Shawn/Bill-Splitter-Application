<?php

/*
* login
*/
require_once 'connect.php';

$sql = <<<EOF
DROP TABLE IF EXISTS bill;
DROP TABLE IF EXISTS notice;
DROP TABLE IF EXISTS splitter;
DROP TABLE IF EXISTS token;
DROP TABLE IF EXISTS user;

CREATE TABLE bill (
     bid INTEGER PRIMARY KEY AUTOINCREMENT,
     key VARCHAR NOT NULL,
     content VARCHAR NOT NULL,
     price VARCHAR NOT NULL,
     total VARCHAR NOT NULL,
     status INTEGER NOT NULL DEFAULT 1,
     type INTEGER NOT NULL DEFAULT 0
    );

CREATE TABLE notice (
     nid INTEGER PRIMARY KEY AUTOINCREMENT,
     uid INTEGER NOT NULL,
     content VARCHAR NOT NULL,
     time VARCHAR NOT NULL,
     status INTEGER NOT NULL DEFAULT 0
    );


CREATE TABLE splitter (
     sid INTEGER PRIMARY KEY AUTOINCREMENT,
     uid INTEGER NOT NULL,
     bid INTEGER NOT NULL,
     price VARCHAR NOT NULL,
     percent VARCHAR NOT NULL,
     status INTEGER NOT NULL DEFAULT 0
    );

CREATE TABLE token (
     tid INTEGER PRIMARY KEY AUTOINCREMENT,
     uid INTEGER NOT NULL,
     token VARCHAR NOT NULL,
     time VARCHAR NOT NULL
    );

CREATE TABLE user (
     uid INTEGER PRIMARY KEY AUTOINCREMENT,
     name VARCHAR DEFAULT NULL,
     email VARCHAR DEFAULT NULL,
     password VARCHAR DEFAULT NULL,
     notice INTEGER NOT NULL DEFAULT 0
    );
EOF;

$result = $db->exec($sql);
if (!$result) {
    echo $db->lastErrorMsg();
} else {
    echo "Database init successfully\n";
}
