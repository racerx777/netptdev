#this is the initial setup for the index server.


#table where all existing indexes are listed.
CREATE TABLE IF NOT EXISTS Bs_Is_Indexes (
  caption varchar(20) NOT NULL DEFAULT '', 
  xml     blob NOT NULL DEFAULT '', 
  PRIMARY KEY caption (caption), 
  UNIQUE (caption)
);


#todo queue where records are listed that need to be (re)indexed or removed.
#todo 'a'=add index, 'r'=remove, 'u'=update. 
#if the record does not exist anymore it will be 
#removed no matter if todo is 'a', 'r' or 'u'.
CREATE TABLE IF NOT EXISTS Bs_Is_Name_Queue (
  ID             INT UNSIGNED NOT NULL DEFAULT 0 AUTO_INCREMENT, 
  recordID       INT NOT NULL DEFAULT 0, 
  todo           CHAR(1) NOT NULL DEFAULT 'a', 
  PRIMARY KEY ID (ID), 
  KEY recordID(recordID)
);

CREATE TABLE IF NOT EXISTS Bs_Is_Name_Words (
  caption      varchar(40) not null default '', 
  soundex      varchar(10) not null default '', 
  stem         varchar(20) not null default '', 
  languages    varchar(20) not null default '', 
  useCount     int         not null default 0, 
  searchCount  int         not null default 0, 
  weight       smallint    not null default 0, 
  PRIMARY KEY  caption (caption), 
  UNIQUE (caption), 
  KEY soundex(soundex), 
  KEY stem (stem)
);


create table IF NOT EXISTS Bs_IS_Name_wordToSource (
  ID                  int not null default 0 auto_increment, 
  wordID              int not null default 0, 
  sourceID            int not null default 0, 
  ranking             smallint not null default 0, 
  primary key (ID), 
  key(wordID), 
  key sourceID (sourceID)
);


