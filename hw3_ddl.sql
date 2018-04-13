-- Create Schema
DROP DATABASE IF EXISTS BBTEAM;

CREATE SCHEMA BBTEAM;
USE BBTEAM;

-- Create tables
CREATE TABLE TeamRoster ( 
  ID INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  Name_First VARCHAR(100),
  Name_Last VARCHAR(150) NOT NULL,
  Street VARCHAR(250),
  City VARCHAR(100),
  State VARCHAR(100),
  Country VARCHAR(100),
  ZipCode CHAR(10) 
    CHECK (ZipCode RLIKE '(?!0{5})(?!9{5})\\d{5}(-(?!0{4})(?!9{4})\\d{4})?' OR ZipCode is NULL),
  PRIMARY KEY (ID),
  UNIQUE KEY Name (Name_Last, Name_First)
) AUTO_INCREMENT=100;

CREATE TABLE Statistics ( 
  ID INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  Player INT(10) UNSIGNED NOT NULL,
  PlayingTimeMin TINYINT(2) UNSIGNED NOT NULL DEFAULT 0 CHECK (PlayingTImeMin < 41),
  PlayingTimeSec TINYINT(2) UNSIGNED NOT NULL DEFAULT 0 CHECK (PlayingTimeSec < 60),
  Points TINYINT(3) UNSIGNED NOT NULL DEFAULT 0,
  Assists TINYINT(3) UNSIGNED NOT NULL DEFAULT 0,
  Rebounds TINYINT(3) UNSIGNED NOT NULL DEFAULT 0,
  PRIMARY KEY (ID),
  FOREIGN KEY (Player) REFERENCES TeamRoster(ID) ON DELETE CASCADE
);

-- Create bbuser - Must be done after table creation, unless we grant CREATE to the user.
DROP USER IF EXISTS bbuser;
GRANT SELECT, INSERT, DELETE, UPDATE ON TeamRoster to bbuser IDENTIFIED BY 'Password!12345';
GRANT SELECT, INSERT, DELETE, UPDATE ON Statistics to bbuser IDENTIFIED BY 'Password!12345';

-- Populate tables with initial data
DELETE FROM TeamRoster;
DELETE FROM Statistics;

INSERT INTO TeamRoster
  (Name_First, Name_Last, Street, City, State, Country, ZipCode)
VALUES
  ('Donald', 'Duck', '1313 S. Harbor Blvd.', 'Anaheim', 'CA', 'USA', '92808-3232'),
  ('Daisy', 'Duck', '1180 Seven Seas Dr.', 'Lake Buena Vista', 'FL', 'USA', '32830'),
  ('Mickey', 'Mouse', '1313 S. Harbor Blvd.', 'Anaheim', 'CA', 'USA', '92808-3232'),
  ('Pluto', 'Dog', '1313 S. Harbor Blvd.', 'Anaheim', 'CA', 'USA', '92808-3232'),
  ('Scrouge', 'McDuck', '1180 Seven Seas Dr.', 'Lake Buena Vista', 'FL', 'USA', '32830'),
  ('Huebert (Huey)', 'Duck', '1110 Seven Seas Dr.', 'Lake Buena Vista', 'FL', 'USA', '32830'),
  ('Deuteronomy (Dewey)', 'Duck', '1110 Seven Seas Dr.', 'Lake Buena Vista', 'FL', 'USA', '32830'),
  ('Louie', 'Duck', '1110 Seven Seas Dr.', 'Lake Buena Vista', 'FL', 'USA', '32830'),
  ('Phooey', 'Duck', '1-1 Maihama Urayasu', 'Chiba Prefecture', 'Disney Tokyo', 'Japan', NULL),
  ('Della', 'Duck', '77700 Boulevard du Parc', 'Coupvray', 'Disney Paris', 'France', NULL)
;

INSERT INTO Statistics
  (ID, Player, PlayingTimeMin, PlayingTimeSec, Points, Assists, Rebounds)
VALUES
  (17, 100, 35, 12, 47, 11, 21),
  (18, 102, 13, 22, 13, 1, 3),
  (19, 103, 10, 0, 18, 2, 4),
  (20, 107, 2, 45, 9, 1, 2),
  (21, 102, 15, 39, 26, 3, 7),
  (22, 100, 29, 47, 27, 9, 8)
;
