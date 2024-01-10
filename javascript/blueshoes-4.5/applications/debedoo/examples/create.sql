DROP TABLE IF EXISTS ExamplePhoneCompany;
CREATE TABLE ExamplePhoneCompany(
  ID          INT            NOT NULL DEFAULT 0 AUTO_INCREMENT, 
  caption     VARCHAR(30)    NOT NULL DEFAULT '', 
  location    VARCHAR(30)    NOT NULL DEFAULT '', 
  PRIMARY KEY ID (ID)
);

INSERT INTO ExamplePhoneCompany (caption, location) VALUES ('Zend Technologies Ltd.', 'Ramat Gan (Israel)');
INSERT INTO ExamplePhoneCompany (caption, location) VALUES ('MySQL AB', 'Uppsala (Sweden)');
INSERT INTO ExamplePhoneCompany (caption, location) VALUES ('MySQL AB', 'Edmonds (USA)');
INSERT INTO ExamplePhoneCompany (caption, location) VALUES ('Red Hat', 'Raleigh (USA)');
INSERT INTO ExamplePhoneCompany (caption, location) VALUES ('Google', 'Mountain View (USA)');
INSERT INTO ExamplePhoneCompany (caption, location) VALUES ('Google', 'Toronto (Canada)');

DROP TABLE IF EXISTS ExamplePhonePerson;
CREATE TABLE ExamplePhonePerson(
  ID                    INT         NOT NULL DEFAULT 0 AUTO_INCREMENT, 
  ExamplePhoneCompanyID INT         NOT NULL DEFAULT 0, 
  firstname             VARCHAR(30) NOT NULL DEFAULT '', 
  lastname              VARCHAR(30) NOT NULL DEFAULT '', 
  phoneNumber           VARCHAR(30) NOT NULL DEFAULT '', 
  notes                 BLOB        NOT NULL DEFAULT '', 
  PRIMARY KEY ID (ID)
);

INSERT INTO ExamplePhonePerson (ExamplePhoneCompanyID, firstname, lastname, phoneNumber, notes) VALUES(1, 'Andi',    'Gutmans',  '123123123', '');
INSERT INTO ExamplePhonePerson (ExamplePhoneCompanyID, firstname, lastname, phoneNumber, notes) VALUES(1, 'Zeev',    'Suraski',  '441441441', '');
INSERT INTO ExamplePhonePerson (ExamplePhoneCompanyID, firstname, lastname, phoneNumber, notes) VALUES(1, 'George',  'Michael',  '441441441', '');
INSERT INTO ExamplePhonePerson (ExamplePhoneCompanyID, firstname, lastname, phoneNumber, notes) VALUES(2, 'Michael', 'Widenius', '323232323', 'aka Monty');
INSERT INTO ExamplePhonePerson (ExamplePhoneCompanyID, firstname, lastname, phoneNumber, notes) VALUES(2, 'Bill',    'Clinton',  '323232323', '');
INSERT INTO ExamplePhonePerson (ExamplePhoneCompanyID, firstname, lastname, phoneNumber, notes) VALUES(2, 'Manu',    'Chao',     '323232323', '');
INSERT INTO ExamplePhonePerson (ExamplePhoneCompanyID, firstname, lastname, phoneNumber, notes) VALUES(3, 'John',    'Miller',   '616161616', '');
INSERT INTO ExamplePhonePerson (ExamplePhoneCompanyID, firstname, lastname, phoneNumber, notes) VALUES(3, 'Peter',   'Johnson',  '987654321', '');
INSERT INTO ExamplePhonePerson (ExamplePhoneCompanyID, firstname, lastname, phoneNumber, notes) VALUES(3, 'Will',    'Smith',    '987654321', '');
INSERT INTO ExamplePhonePerson (ExamplePhoneCompanyID, firstname, lastname, phoneNumber, notes) VALUES(4, 'Peggy',   'Anderson', '123456789', '');
INSERT INTO ExamplePhonePerson (ExamplePhoneCompanyID, firstname, lastname, phoneNumber, notes) VALUES(4, 'Louise',  'Ciccone',  '123456789', '');
INSERT INTO ExamplePhonePerson (ExamplePhoneCompanyID, firstname, lastname, phoneNumber, notes) VALUES(4, 'Jennifer','Lopez',    '123456789', '');
INSERT INTO ExamplePhonePerson (ExamplePhoneCompanyID, firstname, lastname, phoneNumber, notes) VALUES(4, 'Enrique', 'Iglesias', '123456789', '');
INSERT INTO ExamplePhonePerson (ExamplePhoneCompanyID, firstname, lastname, phoneNumber, notes) VALUES(5, 'Richard', 'Peterson', '878787878', '');
INSERT INTO ExamplePhonePerson (ExamplePhoneCompanyID, firstname, lastname, phoneNumber, notes) VALUES(5, 'Robert',  'Miller',   '232323232', '');
INSERT INTO ExamplePhonePerson (ExamplePhoneCompanyID, firstname, lastname, phoneNumber, notes) VALUES(5, 'Robert',  'Redford',  '232323232', '');
INSERT INTO ExamplePhonePerson (ExamplePhoneCompanyID, firstname, lastname, phoneNumber, notes) VALUES(5, 'Cliff',   'Richard',  '232323232', '');
INSERT INTO ExamplePhonePerson (ExamplePhoneCompanyID, firstname, lastname, phoneNumber, notes) VALUES(6, 'Bill',    'Smith',    '171717171', '');
INSERT INTO ExamplePhonePerson (ExamplePhoneCompanyID, firstname, lastname, phoneNumber, notes) VALUES(6, 'John',    'Bush',     '929292929', '');
INSERT INTO ExamplePhonePerson (ExamplePhoneCompanyID, firstname, lastname, phoneNumber, notes) VALUES(6, 'Peg',     'Miller',   '585858585', '');
INSERT INTO ExamplePhonePerson (ExamplePhoneCompanyID, firstname, lastname, phoneNumber, notes) VALUES(6, 'Alanis',  'Morisette','585858585', '');
INSERT INTO ExamplePhonePerson (ExamplePhoneCompanyID, firstname, lastname, phoneNumber, notes) VALUES(6, 'Kylie',   'Minogue',  '585858585', '');

