-- Create Schema
DROP DATABASE IF EXISTS BBDB;

CREATE SCHEMA BBDB;
USE BBDB;

-- Create tables
CREATE TABLE Users (
  UserId INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  Email VARCHAR(256) NOT NULL,
  PasswordHash CHAR(128) NOT NULL,
  Role VARCHAR(32) NOT NULL DEFAULT 'observer' 
    CHECK ( Role in ('observer', 'users', 'manager', 'dba')),
  PRIMARY KEY (UserId),
  UNIQUE KEY (Email)
);


CREATE TABLE Player ( 
  PlayerId INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  FirstName VARCHAR(100),
  LastName VARCHAR(150) NOT NULL,
  Email VARCHAR(256) NOT NULL,
  Street VARCHAR(250),
  City VARCHAR(100),
  State VARCHAR(100),
  Country VARCHAR(100),
  ZipCode CHAR(10) 
    CHECK (ZipCode RLIKE '(?!0{5})(?!9{5})\\d{5}(-(?!0{4})(?!9{4})\\d{4})?' OR ZipCode is NULL),
  PRIMARY KEY (PlayerId),
  UNIQUE KEY Name (LastName, FirstName)
) AUTO_INCREMENT=100;

CREATE TABLE Game ( 
  GameId INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  OpponentName VARCHAR(256) NOT NULL,
  OpponentScore INT(10) UNSIGNED NOT NULL,
  TeamScore INT(10) UNSIGNED NOT NULL,
  GameDate DATE NOT NULL,
  PRIMARY KEY (GameId)
);

CREATE TABLE Stat ( 
  StatId INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  PlayerId INT(10) UNSIGNED NOT NULL,
  GameId INT(10) UNSIGNED NOT NULL,
  PlayingTimeMin TINYINT(2) UNSIGNED NOT NULL DEFAULT 0 CHECK (PlayingTImeMin < 41),
  PlayingTimeSec TINYINT(2) UNSIGNED NOT NULL DEFAULT 0 CHECK (PlayingTimeSec < 60),
  Points TINYINT(3) UNSIGNED NOT NULL DEFAULT 0,
  Assists TINYINT(3) UNSIGNED NOT NULL DEFAULT 0,
  Rebounds TINYINT(3) UNSIGNED NOT NULL DEFAULT 0,
  PRIMARY KEY (StatId),
  FOREIGN KEY (PlayerId) REFERENCES Player(PlayerId) ON DELETE RESTRICT,
  FOREIGN KEY (GameId) REFERENCES Game(GameId) ON DELETE RESTRICT
);

-- Create Stored Procedures
DROP PROCEDURE IF EXISTS set_password;
DELIMITER //
CREATE PROCEDURE set_password
(IN In_UserId INT(10), IN In_Password CHAR(128))
BEGIN
  Update Users SET PasswordHash = In_Password
  WHERE UserId = In_UserId;
END //
DELIMITER ;

-- Create database users
DROP USER IF EXISTS observer;
DROP USER IF EXISTS users;
DROP USER IF EXISTS manager;
DROP USER IF EXISTS dba;
GRANT SELECT ON Game TO observer IDENTIFIED BY 'observer-pw1';
GRANT SELECT ON Stat TO observer IDENTIFIED BY 'observer-pw1';
GRANT SELECT (UserId, Email) ON Users TO observer IDENTIFIED by 'observer-pw1';
GRANT SELECT (PlayerId, FirstName, LastName) ON Player TO observer IDENTIFIED by 'observer-pw1';
GRANT EXECUTE ON PROCEDURE set_password TO observer IDENTIFIED by 'observer-pw1';

GRANT SELECT, INSERT, UPDATE, DELETE ON Game TO users IDENTIFIED BY 'users-pw1';
GRANT SELECT, INSERT, UPDATE, DELETE ON Stat TO users IDENTIFIED BY 'users-pw1';
GRANT SELECT (UserId, Email) ON Users TO users IDENTIFIED by 'users-pw1';
GRANT SELECT (PlayerId, FirstName, LastName) ON Player TO users IDENTIFIED by 'users-pw1';
GRANT EXECUTE ON PROCEDURE set_password TO users IDENTIFIED by 'users-pw1';

GRANT SELECT, INSERT, UPDATE, DELETE ON Game TO manager IDENTIFIED BY 'manager-pw1';
GRANT SELECT, INSERT, UPDATE, DELETE ON Stat TO manager IDENTIFIED BY 'manager-pw1';
GRANT SELECT, INSERT, UPDATE, DELETE ON Users TO manager IDENTIFIED BY 'manager-pw1';
GRANT SELECT, INSERT, UPDATE, DELETE ON Player TO manager IDENTIFIED BY 'manager-pw1';
GRANT EXECUTE ON PROCEDURE set_password TO manager IDENTIFIED by 'manager-pw1';

GRANT ALL ON *.* TO dba IDENTIFIED BY 'dba-pw1';

