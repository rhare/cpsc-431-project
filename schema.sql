-- Create Schema
DROP DATABASE IF EXISTS BBDB;

CREATE SCHEMA BBDB;
USE BBDB;

-- Create tables
CREATE TABLE User (
  UserId INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  Email VARCHAR(256) NOT NULL,
  PasswordHash CHAR(128) NOT NULL,
  Role ENUM('observer', 'users', 'manager', 'dba') NOT NULL DEFAULT 'observer',
  PRIMARY KEY (UserId),
  UNIQUE KEY (Email)
);


CREATE TABLE Team ( 
  TeamId INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  TeamName VARCHAR(256) NOT NULL,
  PRIMARY KEY (TeamId),
  UNIQUE KEY (TeamName)
);

CREATE TABLE Person ( 
  PersonId INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  FirstName VARCHAR(100) NOT NULL,
  LastName VARCHAR(150) NOT NULL,
  Email VARCHAR(256) NOT NULL,
  Street VARCHAR(250),
  City VARCHAR(100),
  State VARCHAR(100),
  Country VARCHAR(100),
  ZipCode CHAR(10) 
    CHECK (ZipCode RLIKE '(?!0{5})(?!9{5})\\d{5}(-(?!0{4})(?!9{4})\\d{4})?' OR ZipCode is NULL),
  PRIMARY KEY (PersonId),
  UNIQUE KEY Name (LastName, FirstName)
) AUTO_INCREMENT=100;

CREATE TABLE Game ( 
  GameId INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  TeamId_A INT(10) UNSIGNED NOT NULL,
  TeamId_B INT(10) UNSIGNED NOT NULL,
  TeamScore_A INT(10) UNSIGNED NOT NULL DEFAULT 0,
  TeamScore_B INT(10) UNSIGNED NOT NULL DEFAULT 0,
  GameDate DATE NOT NULL,
  PRIMARY KEY (GameId),
  FOREIGN KEY (TeamId_A) REFERENCES Team(TeamId) ON DELETE RESTRICT,
  FOREIGN KEY (TeamId_B) REFERENCES Team(TeamId) ON DELETE RESTRICT
);

CREATE TABLE Stat ( 
  StatId INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  PersonId INT(10) UNSIGNED NOT NULL,
  GameId INT(10) UNSIGNED NOT NULL,
  PlayingTimeMin TINYINT(2) UNSIGNED NOT NULL DEFAULT 0 CHECK (PlayingTImeMin < 41),
  PlayingTimeSec TINYINT(2) UNSIGNED NOT NULL DEFAULT 0 CHECK (PlayingTimeSec < 60),
  Points TINYINT(3) UNSIGNED NOT NULL DEFAULT 0,
  Assists TINYINT(3) UNSIGNED NOT NULL DEFAULT 0,
  Rebounds TINYINT(3) UNSIGNED NOT NULL DEFAULT 0,
  PRIMARY KEY (StatId),
  FOREIGN KEY (PersonId) REFERENCES Person(PersonId) ON DELETE RESTRICT,
  FOREIGN KEY (GameId) REFERENCES Game(GameId) ON DELETE RESTRICT
);

CREATE TABLE Coach (
  CoachId INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  PersonId INT(10) UNSIGNED NOT NULL,
  UserId INT(10) UNSIGNED,
  TeamId INT(10) UNSIGNED NOT NULL,
  PRIMARY KEY (CoachId),
  FOREIGN KEY (PersonId) REFERENCES Person(PersonId) ON DELETE RESTRICT,
  FOREIGN KEY (UserId) REFERENCES User(UserId) ON DELETE SET NULL,
  FOREIGN KEY (TeamId) REFERENCES Team(TeamId) ON DELETE RESTRICT
);

CREATE TABLE Player (
  PlayerId INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  PersonId INT(10) UNSIGNED NOT NULL,
  UserId INT(10) UNSIGNED,
  TeamId INT(10) UNSIGNED NOT NULL,
  PRIMARY KEY (PlayerId),
  FOREIGN KEY (PersonId) REFERENCES Person(PersonId) ON DELETE RESTRICT,
  FOREIGN KEY (UserId) REFERENCES User(UserId) ON DELETE SET NULL,
  FOREIGN KEY (TeamId) REFERENCES Team(TeamId) ON DELETE RESTRICT
);

-- Create Stored Procedures
DELIMITER //
CREATE PROCEDURE set_password
(IN In_UserId INT(10), IN In_Password CHAR(128))
BEGIN
  Update User SET PasswordHash = In_Password
  WHERE UserId = In_UserId;
END //
DELIMITER ;

DELIMITER //
CREATE PROCEDURE new_user
(IN In_Email VARCHAR(256), IN In_Password CHAR(128), IN In_Role ENUM('observer', 'users', 'manager', 'dba'))
BEGIN
  Insert into User (Email, PasswordHash, Role) Values (In_Email, In_Password, In_Role);
END //
DELIMITER ;

-- Create database users
DROP USER IF EXISTS observer;
DROP USER IF EXISTS users;
DROP USER IF EXISTS manager;
DROP USER IF EXISTS dba;
GRANT SELECT ON Game TO observer IDENTIFIED BY 'observer-pw1';
GRANT SELECT ON Team TO observer IDENTIFIED BY 'observer-pw1';
GRANT SELECT ON Stat TO observer IDENTIFIED BY 'observer-pw1';
GRANT SELECT ON Player TO observer IDENTIFIED BY 'observer-pw1';
GRANT SELECT ON Coach TO observer IDENTIFIED BY 'observer-pw1';
GRANT SELECT ON User TO observer IDENTIFIED by 'observer-pw1';
GRANT SELECT (PersonId, FirstName, LastName) ON Person TO observer IDENTIFIED by 'observer-pw1';
GRANT EXECUTE ON PROCEDURE set_password TO observer IDENTIFIED by 'observer-pw1';
GRANT EXECUTE ON PROCEDURE new_user TO observer IDENTIFIED by 'observer-pw1';

GRANT SELECT, INSERT, UPDATE, DELETE ON Game TO users IDENTIFIED BY 'users-pw1';
GRANT SELECT, INSERT, UPDATE, DELETE ON Stat TO users IDENTIFIED BY 'users-pw1';
GRANT SELECT, INSERT, UPDATE, DELETE ON Team TO users IDENTIFIED BY 'users-pw1';
GRANT SELECT ON Player TO users IDENTIFIED BY 'users-pw1';
GRANT SELECT ON Coach TO users IDENTIFIED BY 'users-pw1';
GRANT SELECT ON User TO users IDENTIFIED by 'users-pw1';
GRANT SELECT (PersonId, FirstName, LastName) ON Person TO users IDENTIFIED by 'users-pw1';
GRANT EXECUTE ON PROCEDURE set_password TO users IDENTIFIED by 'users-pw1';
GRANT EXECUTE ON PROCEDURE new_user TO users IDENTIFIED by 'users-pw1';

GRANT SELECT, INSERT, UPDATE, DELETE ON Game TO manager IDENTIFIED BY 'manager-pw1';
GRANT SELECT, INSERT, UPDATE, DELETE ON Team TO manager IDENTIFIED BY 'manager-pw1';
GRANT SELECT, INSERT, UPDATE, DELETE ON Stat TO manager IDENTIFIED BY 'manager-pw1';
GRANT SELECT, INSERT, UPDATE, DELETE ON User TO manager IDENTIFIED BY 'manager-pw1';
GRANT SELECT, INSERT, UPDATE, DELETE ON Person TO manager IDENTIFIED BY 'manager-pw1';
GRANT SELECT, INSERT, UPDATE, DELETE ON Player TO manager IDENTIFIED BY 'manager-pw1';
GRANT SELECT, INSERT, UPDATE, DELETE ON Coach TO manager IDENTIFIED BY 'manager-pw1';
GRANT EXECUTE ON PROCEDURE set_password TO manager IDENTIFIED by 'manager-pw1';
GRANT EXECUTE ON PROCEDURE new_user TO manager IDENTIFIED by 'manager-pw1';

GRANT ALL ON * TO dba IDENTIFIED BY 'dba-pw1';

INSERT INTO User ( Email, PasswordHash, Role) Values 
  ('root@root.com', '$2y$10$z/dcwNUj72MzvvMgqm0jsOzI0oJli.e9W75gP7RJuP.73f.KKjJ2a', 'dba'),
  ('rhare@csu.fullerton.edu', '$2y$10$z/dcwNUj72MzvvMgqm0jsOzI0oJli.e9W75gP7RJuP.73f.KKjJ2a', 'manager');

INSERT INTO Team (TeamName) Values 
  ('Owls'),
  ('Titans'),
  ('Knights');
