#this is the initial setup for the index server.

#drop database cmtIndexServer;
CREATE DATABASE IF NOT EXISTS bs_indexServer;

#this needs to be done with every website user that needs access to this db.
#there is no sensitive data in it.
INSERT INTO 
# set rights for ecg user
DELETE FROM mysql.db WHERE User = 'bs_ecg' AND Db = 'bs_ecg';
INSERT INTO mysql.db SET 
Host            = 'localhost', 
Db              = 'bs_ecg', 
User            = 'bs_ecg', 
Select_priv     = 'Y', 
Insert_priv     = 'Y', 
Update_priv     = 'Y', 
Delete_priv     = 'Y', 
Create_priv     = 'Y', 
Drop_priv       = 'Y', 
Grant_priv      = 'N', 
References_priv = 'Y', 
Index_priv      = 'Y', 
Alter_priv      = 'Y';


DROP TABLE IF EXISTS bs_indexServer.NoiseWordsEn;
CREATE TABLE IF NOT EXISTS bs_indexServer.NoiseWordsEn (
  caption             varchar(20) not null default '', 
  primary key (caption), 
  unique (caption)
);
INSERT INTO bs_indexServer.NoiseWordsEn VALUES('about');
INSERT INTO bs_indexServer.NoiseWordsEn VALUES('above');
INSERT INTO bs_indexServer.NoiseWordsEn VALUES('according');
INSERT INTO bs_indexServer.NoiseWordsEn VALUES('across');
INSERT INTO bs_indexServer.NoiseWordsEn VALUES('actually');
INSERT INTO bs_indexServer.NoiseWordsEn VALUES('adj');
INSERT INTO bs_indexServer.NoiseWordsEn VALUES('after');
INSERT INTO bs_indexServer.NoiseWordsEn VALUES('afterwards');
INSERT INTO bs_indexServer.NoiseWordsEn VALUES('again');
INSERT INTO bs_indexServer.NoiseWordsEn VALUES('against');
INSERT INTO bs_indexServer.NoiseWordsEn VALUES('all');
INSERT INTO bs_indexServer.NoiseWordsEn VALUES('almost');
INSERT INTO bs_indexServer.NoiseWordsEn VALUES('alone');
INSERT INTO bs_indexServer.NoiseWordsEn VALUES('along');
INSERT INTO bs_indexServer.NoiseWordsEn VALUES('already');
INSERT INTO bs_indexServer.NoiseWordsEn VALUES('also');
INSERT INTO bs_indexServer.NoiseWordsEn VALUES('although');
INSERT INTO bs_indexServer.NoiseWordsEn VALUES('always');
INSERT INTO bs_indexServer.NoiseWordsEn VALUES('among');
INSERT INTO bs_indexServer.NoiseWordsEn VALUES('amongst');
INSERT INTO bs_indexServer.NoiseWordsEn VALUES('an');
INSERT INTO bs_indexServer.NoiseWordsEn VALUES('and');
INSERT INTO bs_indexServer.NoiseWordsEn VALUES('another');
INSERT INTO bs_indexServer.NoiseWordsEn VALUES('any');
INSERT INTO bs_indexServer.NoiseWordsEn VALUES('anyhow');
INSERT INTO bs_indexServer.NoiseWordsEn VALUES('anyone');
INSERT INTO bs_indexServer.NoiseWordsEn VALUES('anything');
INSERT INTO bs_indexServer.NoiseWordsEn VALUES('anywhere');
INSERT INTO bs_indexServer.NoiseWordsEn VALUES('are');
INSERT INTO bs_indexServer.NoiseWordsEn VALUES('aren\'t');
INSERT INTO bs_indexServer.NoiseWordsEn VALUES('around');
INSERT INTO bs_indexServer.NoiseWordsEn VALUES('as');
INSERT INTO bs_indexServer.NoiseWordsEn VALUES('at');
INSERT INTO bs_indexServer.NoiseWordsEn VALUES('be');
INSERT INTO bs_indexServer.NoiseWordsEn VALUES('became');
INSERT INTO bs_indexServer.NoiseWordsEn VALUES('because');
INSERT INTO bs_indexServer.NoiseWordsEn VALUES('become');
INSERT INTO bs_indexServer.NoiseWordsEn VALUES('becomes');
INSERT INTO bs_indexServer.NoiseWordsEn VALUES('becoming');
INSERT INTO bs_indexServer.NoiseWordsEn VALUES('been');
INSERT INTO bs_indexServer.NoiseWordsEn VALUES('before');
INSERT INTO bs_indexServer.NoiseWordsEn VALUES('beforehand');
INSERT INTO bs_indexServer.NoiseWordsEn VALUES('begin');
INSERT INTO bs_indexServer.NoiseWordsEn VALUES('beginning');
INSERT INTO bs_indexServer.NoiseWordsEn VALUES('behind');
INSERT INTO bs_indexServer.NoiseWordsEn VALUES('being');
INSERT INTO bs_indexServer.NoiseWordsEn VALUES('below');
INSERT INTO bs_indexServer.NoiseWordsEn VALUES('beside');
INSERT INTO bs_indexServer.NoiseWordsEn VALUES('besides');
INSERT INTO bs_indexServer.NoiseWordsEn VALUES('between');
INSERT INTO bs_indexServer.NoiseWordsEn VALUES('beyonds');
INSERT INTO bs_indexServer.NoiseWordsEn VALUES('billion');
INSERT INTO bs_indexServer.NoiseWordsEn VALUES('both');
INSERT INTO bs_indexServer.NoiseWordsEn VALUES('but');
INSERT INTO bs_indexServer.NoiseWordsEn VALUES('by');
INSERT INTO bs_indexServer.NoiseWordsEn VALUES('can');
INSERT INTO bs_indexServer.NoiseWordsEn VALUES('can\'t');
INSERT INTO bs_indexServer.NoiseWordsEn VALUES('cannot');
INSERT INTO bs_indexServer.NoiseWordsEn VALUES('caption');
INSERT INTO bs_indexServer.NoiseWordsEn VALUES('co');
INSERT INTO bs_indexServer.NoiseWordsEn VALUES('co.');
INSERT INTO bs_indexServer.NoiseWordsEn VALUES('copy');
INSERT INTO bs_indexServer.NoiseWordsEn VALUES('could');
INSERT INTO bs_indexServer.NoiseWordsEn VALUES('couldn\'t');
INSERT INTO bs_indexServer.NoiseWordsEn VALUES('did');
INSERT INTO bs_indexServer.NoiseWordsEn VALUES('didn\'t');
INSERT INTO bs_indexServer.NoiseWordsEn VALUES('do');
INSERT INTO bs_indexServer.NoiseWordsEn VALUES('does');
INSERT INTO bs_indexServer.NoiseWordsEn VALUES('doesn\'t');
INSERT INTO bs_indexServer.NoiseWordsEn VALUES('don\'t');
INSERT INTO bs_indexServer.NoiseWordsEn VALUES('down');
INSERT INTO bs_indexServer.NoiseWordsEn VALUES('during');
INSERT INTO bs_indexServer.NoiseWordsEn VALUES('each');
INSERT INTO bs_indexServer.NoiseWordsEn VALUES('eg');
INSERT INTO bs_indexServer.NoiseWordsEn VALUES('eight');
INSERT INTO bs_indexServer.NoiseWordsEn VALUES('eighty');
INSERT INTO bs_indexServer.NoiseWordsEn VALUES('either');
INSERT INTO bs_indexServer.NoiseWordsEn VALUES('else');
INSERT INTO bs_indexServer.NoiseWordsEn VALUES('elsewhere');
INSERT INTO bs_indexServer.NoiseWordsEn VALUES('end');
INSERT INTO bs_indexServer.NoiseWordsEn VALUES('ending');
INSERT INTO bs_indexServer.NoiseWordsEn VALUES('enough');
INSERT INTO bs_indexServer.NoiseWordsEn VALUES('etc');
INSERT INTO bs_indexServer.NoiseWordsEn VALUES('even');
INSERT INTO bs_indexServer.NoiseWordsEn VALUES('ever');
INSERT INTO bs_indexServer.NoiseWordsEn VALUES('every');
INSERT INTO bs_indexServer.NoiseWordsEn VALUES('everyone');
INSERT INTO bs_indexServer.NoiseWordsEn VALUES('everything');
INSERT INTO bs_indexServer.NoiseWordsEn VALUES('everywhere');
INSERT INTO bs_indexServer.NoiseWordsEn VALUES('except');
INSERT INTO bs_indexServer.NoiseWordsEn VALUES('few');
INSERT INTO bs_indexServer.NoiseWordsEn VALUES('fifty');
INSERT INTO bs_indexServer.NoiseWordsEn VALUES('first');
INSERT INTO bs_indexServer.NoiseWordsEn VALUES('five');
INSERT INTO bs_indexServer.NoiseWordsEn VALUES('for');
INSERT INTO bs_indexServer.NoiseWordsEn VALUES('former');
INSERT INTO bs_indexServer.NoiseWordsEn VALUES('formerly');
INSERT INTO bs_indexServer.NoiseWordsEn VALUES('forty');
INSERT INTO bs_indexServer.NoiseWordsEn VALUES('found');
INSERT INTO bs_indexServer.NoiseWordsEn VALUES('four');
INSERT INTO bs_indexServer.NoiseWordsEn VALUES('from');
INSERT INTO bs_indexServer.NoiseWordsEn VALUES('further');
INSERT INTO bs_indexServer.NoiseWordsEn VALUES('get');
INSERT INTO bs_indexServer.NoiseWordsEn VALUES('go');
INSERT INTO bs_indexServer.NoiseWordsEn VALUES('got');
INSERT INTO bs_indexServer.NoiseWordsEn VALUES('had');
INSERT INTO bs_indexServer.NoiseWordsEn VALUES('has');
INSERT INTO bs_indexServer.NoiseWordsEn VALUES('hasn\'t');
INSERT INTO bs_indexServer.NoiseWordsEn VALUES('have');
INSERT INTO bs_indexServer.NoiseWordsEn VALUES('haven\'t');
INSERT INTO bs_indexServer.NoiseWordsEn VALUES('he');
INSERT INTO bs_indexServer.NoiseWordsEn VALUES('he\'d');
INSERT INTO bs_indexServer.NoiseWordsEn VALUES('he\'ll');
INSERT INTO bs_indexServer.NoiseWordsEn VALUES('he\'s');
INSERT INTO bs_indexServer.NoiseWordsEn VALUES('hence');
INSERT INTO bs_indexServer.NoiseWordsEn VALUES('her');
INSERT INTO bs_indexServer.NoiseWordsEn VALUES('here');
INSERT INTO bs_indexServer.NoiseWordsEn VALUES('here\'s');
INSERT INTO bs_indexServer.NoiseWordsEn VALUES('hereafter');
INSERT INTO bs_indexServer.NoiseWordsEn VALUES('hereby');
INSERT INTO bs_indexServer.NoiseWordsEn VALUES('herein');
INSERT INTO bs_indexServer.NoiseWordsEn VALUES('hereupon');
INSERT INTO bs_indexServer.NoiseWordsEn VALUES('hers');
INSERT INTO bs_indexServer.NoiseWordsEn VALUES('herself');
INSERT INTO bs_indexServer.NoiseWordsEn VALUES('him');
INSERT INTO bs_indexServer.NoiseWordsEn VALUES('himself');
INSERT INTO bs_indexServer.NoiseWordsEn VALUES('his');
INSERT INTO bs_indexServer.NoiseWordsEn VALUES('how');
INSERT INTO bs_indexServer.NoiseWordsEn VALUES('however');
INSERT INTO bs_indexServer.NoiseWordsEn VALUES('hundred');
INSERT INTO bs_indexServer.NoiseWordsEn VALUES('i\'d');
INSERT INTO bs_indexServer.NoiseWordsEn VALUES('i\'ll');
INSERT INTO bs_indexServer.NoiseWordsEn VALUES('i\'m');
INSERT INTO bs_indexServer.NoiseWordsEn VALUES('i\'ve');
INSERT INTO bs_indexServer.NoiseWordsEn VALUES('ie');
INSERT INTO bs_indexServer.NoiseWordsEn VALUES('if');
INSERT INTO bs_indexServer.NoiseWordsEn VALUES('in');
INSERT INTO bs_indexServer.NoiseWordsEn VALUES('inc.');
INSERT INTO bs_indexServer.NoiseWordsEn VALUES('include');
INSERT INTO bs_indexServer.NoiseWordsEn VALUES('indeed');
INSERT INTO bs_indexServer.NoiseWordsEn VALUES('instead');
INSERT INTO bs_indexServer.NoiseWordsEn VALUES('into');
INSERT INTO bs_indexServer.NoiseWordsEn VALUES('is');
INSERT INTO bs_indexServer.NoiseWordsEn VALUES('isn\'t');
INSERT INTO bs_indexServer.NoiseWordsEn VALUES('it');
INSERT INTO bs_indexServer.NoiseWordsEn VALUES('it\'s');
INSERT INTO bs_indexServer.NoiseWordsEn VALUES('its');
INSERT INTO bs_indexServer.NoiseWordsEn VALUES('itself');
INSERT INTO bs_indexServer.NoiseWordsEn VALUES('just');
INSERT INTO bs_indexServer.NoiseWordsEn VALUES('last');
INSERT INTO bs_indexServer.NoiseWordsEn VALUES('later');
INSERT INTO bs_indexServer.NoiseWordsEn VALUES('latter');
INSERT INTO bs_indexServer.NoiseWordsEn VALUES('latterly');
INSERT INTO bs_indexServer.NoiseWordsEn VALUES('least');
INSERT INTO bs_indexServer.NoiseWordsEn VALUES('less');
INSERT INTO bs_indexServer.NoiseWordsEn VALUES('let');
INSERT INTO bs_indexServer.NoiseWordsEn VALUES('let\'s');
INSERT INTO bs_indexServer.NoiseWordsEn VALUES('like');
INSERT INTO bs_indexServer.NoiseWordsEn VALUES('likely');
INSERT INTO bs_indexServer.NoiseWordsEn VALUES('ltd');
INSERT INTO bs_indexServer.NoiseWordsEn VALUES('made');
INSERT INTO bs_indexServer.NoiseWordsEn VALUES('make');
INSERT INTO bs_indexServer.NoiseWordsEn VALUES('makes');
INSERT INTO bs_indexServer.NoiseWordsEn VALUES('many');
INSERT INTO bs_indexServer.NoiseWordsEn VALUES('maybe');
INSERT INTO bs_indexServer.NoiseWordsEn VALUES('me');
INSERT INTO bs_indexServer.NoiseWordsEn VALUES('meantime');
INSERT INTO bs_indexServer.NoiseWordsEn VALUES('meanwhile');
INSERT INTO bs_indexServer.NoiseWordsEn VALUES('might');
INSERT INTO bs_indexServer.NoiseWordsEn VALUES('million');
INSERT INTO bs_indexServer.NoiseWordsEn VALUES('miss');
INSERT INTO bs_indexServer.NoiseWordsEn VALUES('more');
INSERT INTO bs_indexServer.NoiseWordsEn VALUES('moreover');
INSERT INTO bs_indexServer.NoiseWordsEn VALUES('most');
INSERT INTO bs_indexServer.NoiseWordsEn VALUES('mostly');
INSERT INTO bs_indexServer.NoiseWordsEn VALUES('mr');
INSERT INTO bs_indexServer.NoiseWordsEn VALUES('mrs');
INSERT INTO bs_indexServer.NoiseWordsEn VALUES('much');
INSERT INTO bs_indexServer.NoiseWordsEn VALUES('must');
INSERT INTO bs_indexServer.NoiseWordsEn VALUES('my');
INSERT INTO bs_indexServer.NoiseWordsEn VALUES('myself');
INSERT INTO bs_indexServer.NoiseWordsEn VALUES('namely');
INSERT INTO bs_indexServer.NoiseWordsEn VALUES('need');
INSERT INTO bs_indexServer.NoiseWordsEn VALUES('neither');
INSERT INTO bs_indexServer.NoiseWordsEn VALUES('never');
INSERT INTO bs_indexServer.NoiseWordsEn VALUES('nevertheless');
INSERT INTO bs_indexServer.NoiseWordsEn VALUES('new');
INSERT INTO bs_indexServer.NoiseWordsEn VALUES('next');
INSERT INTO bs_indexServer.NoiseWordsEn VALUES('nine');
INSERT INTO bs_indexServer.NoiseWordsEn VALUES('ninety');
INSERT INTO bs_indexServer.NoiseWordsEn VALUES('no');
INSERT INTO bs_indexServer.NoiseWordsEn VALUES('nobody');
INSERT INTO bs_indexServer.NoiseWordsEn VALUES('none');
INSERT INTO bs_indexServer.NoiseWordsEn VALUES('nonetheless');
INSERT INTO bs_indexServer.NoiseWordsEn VALUES('noone');
INSERT INTO bs_indexServer.NoiseWordsEn VALUES('nor');
INSERT INTO bs_indexServer.NoiseWordsEn VALUES('not');
INSERT INTO bs_indexServer.NoiseWordsEn VALUES('nothing');
INSERT INTO bs_indexServer.NoiseWordsEn VALUES('now');
INSERT INTO bs_indexServer.NoiseWordsEn VALUES('nowhere');
INSERT INTO bs_indexServer.NoiseWordsEn VALUES('of');
INSERT INTO bs_indexServer.NoiseWordsEn VALUES('off');
INSERT INTO bs_indexServer.NoiseWordsEn VALUES('often');
INSERT INTO bs_indexServer.NoiseWordsEn VALUES('on');
INSERT INTO bs_indexServer.NoiseWordsEn VALUES('once');
INSERT INTO bs_indexServer.NoiseWordsEn VALUES('one');
INSERT INTO bs_indexServer.NoiseWordsEn VALUES('one\'s');
INSERT INTO bs_indexServer.NoiseWordsEn VALUES('only');
INSERT INTO bs_indexServer.NoiseWordsEn VALUES('onto');
INSERT INTO bs_indexServer.NoiseWordsEn VALUES('or');
INSERT INTO bs_indexServer.NoiseWordsEn VALUES('other');
INSERT INTO bs_indexServer.NoiseWordsEn VALUES('others');
INSERT INTO bs_indexServer.NoiseWordsEn VALUES('otherwise');
INSERT INTO bs_indexServer.NoiseWordsEn VALUES('our');
INSERT INTO bs_indexServer.NoiseWordsEn VALUES('ours');
INSERT INTO bs_indexServer.NoiseWordsEn VALUES('ourselves');
INSERT INTO bs_indexServer.NoiseWordsEn VALUES('out');
INSERT INTO bs_indexServer.NoiseWordsEn VALUES('over');
INSERT INTO bs_indexServer.NoiseWordsEn VALUES('overall');
INSERT INTO bs_indexServer.NoiseWordsEn VALUES('own');
INSERT INTO bs_indexServer.NoiseWordsEn VALUES('per');
INSERT INTO bs_indexServer.NoiseWordsEn VALUES('perhaps');
INSERT INTO bs_indexServer.NoiseWordsEn VALUES('rather');
INSERT INTO bs_indexServer.NoiseWordsEn VALUES('recent');
INSERT INTO bs_indexServer.NoiseWordsEn VALUES('recently');
INSERT INTO bs_indexServer.NoiseWordsEn VALUES('said');
INSERT INTO bs_indexServer.NoiseWordsEn VALUES('same');
INSERT INTO bs_indexServer.NoiseWordsEn VALUES('say');
INSERT INTO bs_indexServer.NoiseWordsEn VALUES('seem');
INSERT INTO bs_indexServer.NoiseWordsEn VALUES('seemed');
INSERT INTO bs_indexServer.NoiseWordsEn VALUES('seeming');
INSERT INTO bs_indexServer.NoiseWordsEn VALUES('seems');
INSERT INTO bs_indexServer.NoiseWordsEn VALUES('seven');
INSERT INTO bs_indexServer.NoiseWordsEn VALUES('seventy');
INSERT INTO bs_indexServer.NoiseWordsEn VALUES('several');
INSERT INTO bs_indexServer.NoiseWordsEn VALUES('she');
INSERT INTO bs_indexServer.NoiseWordsEn VALUES('she\'d');
INSERT INTO bs_indexServer.NoiseWordsEn VALUES('she\'ll');
INSERT INTO bs_indexServer.NoiseWordsEn VALUES('she\'s');
INSERT INTO bs_indexServer.NoiseWordsEn VALUES('should');
INSERT INTO bs_indexServer.NoiseWordsEn VALUES('shouldn\'t');
INSERT INTO bs_indexServer.NoiseWordsEn VALUES('since');
INSERT INTO bs_indexServer.NoiseWordsEn VALUES('six');
INSERT INTO bs_indexServer.NoiseWordsEn VALUES('sixty');
INSERT INTO bs_indexServer.NoiseWordsEn VALUES('so');
INSERT INTO bs_indexServer.NoiseWordsEn VALUES('some');
INSERT INTO bs_indexServer.NoiseWordsEn VALUES('somehow');
INSERT INTO bs_indexServer.NoiseWordsEn VALUES('someone');
INSERT INTO bs_indexServer.NoiseWordsEn VALUES('something');
INSERT INTO bs_indexServer.NoiseWordsEn VALUES('sometime');
INSERT INTO bs_indexServer.NoiseWordsEn VALUES('sometimes');
INSERT INTO bs_indexServer.NoiseWordsEn VALUES('somewhere');
INSERT INTO bs_indexServer.NoiseWordsEn VALUES('still');
INSERT INTO bs_indexServer.NoiseWordsEn VALUES('stop');
INSERT INTO bs_indexServer.NoiseWordsEn VALUES('stoplist');
INSERT INTO bs_indexServer.NoiseWordsEn VALUES('such');
INSERT INTO bs_indexServer.NoiseWordsEn VALUES('take');
INSERT INTO bs_indexServer.NoiseWordsEn VALUES('takin');
INSERT INTO bs_indexServer.NoiseWordsEn VALUES('tell');
INSERT INTO bs_indexServer.NoiseWordsEn VALUES('ten');
INSERT INTO bs_indexServer.NoiseWordsEn VALUES('than');
INSERT INTO bs_indexServer.NoiseWordsEn VALUES('that');
INSERT INTO bs_indexServer.NoiseWordsEn VALUES('that\'ll');
INSERT INTO bs_indexServer.NoiseWordsEn VALUES('that\'s');
INSERT INTO bs_indexServer.NoiseWordsEn VALUES('that\'ve');
INSERT INTO bs_indexServer.NoiseWordsEn VALUES('the');
INSERT INTO bs_indexServer.NoiseWordsEn VALUES('their');
INSERT INTO bs_indexServer.NoiseWordsEn VALUES('them');
INSERT INTO bs_indexServer.NoiseWordsEn VALUES('themselves');
INSERT INTO bs_indexServer.NoiseWordsEn VALUES('then');
INSERT INTO bs_indexServer.NoiseWordsEn VALUES('thence');
INSERT INTO bs_indexServer.NoiseWordsEn VALUES('there');
INSERT INTO bs_indexServer.NoiseWordsEn VALUES('there\'d');
INSERT INTO bs_indexServer.NoiseWordsEn VALUES('there\'ll');
INSERT INTO bs_indexServer.NoiseWordsEn VALUES('there\'re');
INSERT INTO bs_indexServer.NoiseWordsEn VALUES('there\'s');
INSERT INTO bs_indexServer.NoiseWordsEn VALUES('there\'ve');
INSERT INTO bs_indexServer.NoiseWordsEn VALUES('thereafter');
INSERT INTO bs_indexServer.NoiseWordsEn VALUES('thereby');
INSERT INTO bs_indexServer.NoiseWordsEn VALUES('therefore');
INSERT INTO bs_indexServer.NoiseWordsEn VALUES('therein');
INSERT INTO bs_indexServer.NoiseWordsEn VALUES('thereupon');
INSERT INTO bs_indexServer.NoiseWordsEn VALUES('these');
INSERT INTO bs_indexServer.NoiseWordsEn VALUES('they');
INSERT INTO bs_indexServer.NoiseWordsEn VALUES('they\'d');
INSERT INTO bs_indexServer.NoiseWordsEn VALUES('they\'ll');
INSERT INTO bs_indexServer.NoiseWordsEn VALUES('they\'re');
INSERT INTO bs_indexServer.NoiseWordsEn VALUES('they\'ve');
INSERT INTO bs_indexServer.NoiseWordsEn VALUES('thirty');
INSERT INTO bs_indexServer.NoiseWordsEn VALUES('this');
INSERT INTO bs_indexServer.NoiseWordsEn VALUES('those');
INSERT INTO bs_indexServer.NoiseWordsEn VALUES('though');
INSERT INTO bs_indexServer.NoiseWordsEn VALUES('thousand');
INSERT INTO bs_indexServer.NoiseWordsEn VALUES('three');
INSERT INTO bs_indexServer.NoiseWordsEn VALUES('through');
INSERT INTO bs_indexServer.NoiseWordsEn VALUES('throughout');
INSERT INTO bs_indexServer.NoiseWordsEn VALUES('thru');
INSERT INTO bs_indexServer.NoiseWordsEn VALUES('thus');
INSERT INTO bs_indexServer.NoiseWordsEn VALUES('to');
INSERT INTO bs_indexServer.NoiseWordsEn VALUES('today');
INSERT INTO bs_indexServer.NoiseWordsEn VALUES('together');
INSERT INTO bs_indexServer.NoiseWordsEn VALUES('too');
INSERT INTO bs_indexServer.NoiseWordsEn VALUES('toward');
INSERT INTO bs_indexServer.NoiseWordsEn VALUES('towards');
INSERT INTO bs_indexServer.NoiseWordsEn VALUES('trillion');
INSERT INTO bs_indexServer.NoiseWordsEn VALUES('twenty');
INSERT INTO bs_indexServer.NoiseWordsEn VALUES('two');
INSERT INTO bs_indexServer.NoiseWordsEn VALUES('under');
INSERT INTO bs_indexServer.NoiseWordsEn VALUES('unless');
INSERT INTO bs_indexServer.NoiseWordsEn VALUES('unlike');
INSERT INTO bs_indexServer.NoiseWordsEn VALUES('unlikely');
INSERT INTO bs_indexServer.NoiseWordsEn VALUES('until');
INSERT INTO bs_indexServer.NoiseWordsEn VALUES('up');
INSERT INTO bs_indexServer.NoiseWordsEn VALUES('upon');
INSERT INTO bs_indexServer.NoiseWordsEn VALUES('us');
INSERT INTO bs_indexServer.NoiseWordsEn VALUES('used');
INSERT INTO bs_indexServer.NoiseWordsEn VALUES('using');
INSERT INTO bs_indexServer.NoiseWordsEn VALUES('very');
INSERT INTO bs_indexServer.NoiseWordsEn VALUES('via');
INSERT INTO bs_indexServer.NoiseWordsEn VALUES('want');
INSERT INTO bs_indexServer.NoiseWordsEn VALUES('was');
INSERT INTO bs_indexServer.NoiseWordsEn VALUES('wasn\'t');
INSERT INTO bs_indexServer.NoiseWordsEn VALUES('way');
INSERT INTO bs_indexServer.NoiseWordsEn VALUES('we');
INSERT INTO bs_indexServer.NoiseWordsEn VALUES('we\'d');
INSERT INTO bs_indexServer.NoiseWordsEn VALUES('we\'ll');
INSERT INTO bs_indexServer.NoiseWordsEn VALUES('we\'re');
INSERT INTO bs_indexServer.NoiseWordsEn VALUES('we\'ve');
INSERT INTO bs_indexServer.NoiseWordsEn VALUES('well');
INSERT INTO bs_indexServer.NoiseWordsEn VALUES('were');
INSERT INTO bs_indexServer.NoiseWordsEn VALUES('weren\'t');
INSERT INTO bs_indexServer.NoiseWordsEn VALUES('what');
INSERT INTO bs_indexServer.NoiseWordsEn VALUES('what\'ll');
INSERT INTO bs_indexServer.NoiseWordsEn VALUES('what\'s');
INSERT INTO bs_indexServer.NoiseWordsEn VALUES('what\'ve');
INSERT INTO bs_indexServer.NoiseWordsEn VALUES('whatever');
INSERT INTO bs_indexServer.NoiseWordsEn VALUES('when');
INSERT INTO bs_indexServer.NoiseWordsEn VALUES('whence');
INSERT INTO bs_indexServer.NoiseWordsEn VALUES('whenever');
INSERT INTO bs_indexServer.NoiseWordsEn VALUES('where');
INSERT INTO bs_indexServer.NoiseWordsEn VALUES('where\'s');
INSERT INTO bs_indexServer.NoiseWordsEn VALUES('whereafter');
INSERT INTO bs_indexServer.NoiseWordsEn VALUES('whereas');
INSERT INTO bs_indexServer.NoiseWordsEn VALUES('whereby');
INSERT INTO bs_indexServer.NoiseWordsEn VALUES('wherein');
INSERT INTO bs_indexServer.NoiseWordsEn VALUES('whereupon');
INSERT INTO bs_indexServer.NoiseWordsEn VALUES('wherever');
INSERT INTO bs_indexServer.NoiseWordsEn VALUES('whether');
INSERT INTO bs_indexServer.NoiseWordsEn VALUES('which');
INSERT INTO bs_indexServer.NoiseWordsEn VALUES('while');
INSERT INTO bs_indexServer.NoiseWordsEn VALUES('whither');
INSERT INTO bs_indexServer.NoiseWordsEn VALUES('who');
INSERT INTO bs_indexServer.NoiseWordsEn VALUES('who\'d');
INSERT INTO bs_indexServer.NoiseWordsEn VALUES('who\'ll');
INSERT INTO bs_indexServer.NoiseWordsEn VALUES('who\'s');
INSERT INTO bs_indexServer.NoiseWordsEn VALUES('whoever');
INSERT INTO bs_indexServer.NoiseWordsEn VALUES('whole');
INSERT INTO bs_indexServer.NoiseWordsEn VALUES('whom');
INSERT INTO bs_indexServer.NoiseWordsEn VALUES('whomever');
INSERT INTO bs_indexServer.NoiseWordsEn VALUES('whose');
INSERT INTO bs_indexServer.NoiseWordsEn VALUES('why');
INSERT INTO bs_indexServer.NoiseWordsEn VALUES('will');
INSERT INTO bs_indexServer.NoiseWordsEn VALUES('with');
INSERT INTO bs_indexServer.NoiseWordsEn VALUES('within');
INSERT INTO bs_indexServer.NoiseWordsEn VALUES('without');
INSERT INTO bs_indexServer.NoiseWordsEn VALUES('won\'t');
INSERT INTO bs_indexServer.NoiseWordsEn VALUES('would');
INSERT INTO bs_indexServer.NoiseWordsEn VALUES('wouldn\'t');
INSERT INTO bs_indexServer.NoiseWordsEn VALUES('yes');
INSERT INTO bs_indexServer.NoiseWordsEn VALUES('yet');
INSERT INTO bs_indexServer.NoiseWordsEn VALUES('you');
INSERT INTO bs_indexServer.NoiseWordsEn VALUES('you\'d');
INSERT INTO bs_indexServer.NoiseWordsEn VALUES('you\'ll');
INSERT INTO bs_indexServer.NoiseWordsEn VALUES('you\'re');
INSERT INTO bs_indexServer.NoiseWordsEn VALUES('you\'ve');
INSERT INTO bs_indexServer.NoiseWordsEn VALUES('your');
INSERT INTO bs_indexServer.NoiseWordsEn VALUES('yours');
INSERT INTO bs_indexServer.NoiseWordsEn VALUES('yourself');
INSERT INTO bs_indexServer.NoiseWordsEn VALUES('yourselves');

#table where all existing indexes are listed.
#drop table if exists bs_indexServer.Indexes;
CREATE TABLE IF NOT EXISTS bs_indexServer.Indexes (
  caption varchar(20) NOT NULL DEFAULT '', 
  xml     blob NOT NULL DEFAULT '', 
  PRIMARY KEY caption (caption), 
  UNIQUE (caption)
);

#todo queue where records are listed that need to be (re)indexed or removed.
#todo 'a'=add index, 'r'=remove. if the record does not exist anymore it will be 
#removed no matter if todo is 'a' or 'r'.
#drop table if exists bs_indexServer.Queue;
CREATE TABLE IF NOT EXISTS bs_indexServer.Queue (
  ID             INT UNSIGNED NOT NULL DEFAULT 0 AUTO_INCREMENT, 
  IndexesCaption INT NOT NULL DEFAULT 0, 
  recordID       INT NOT NULL DEFAULT 0, 
  todo           CHAR(1) NOT NULL DEFAULT 'a', 
  PRIMARY KEY ID (ID), 
  KEY IndexesCaption(IndexesCaption), 
  KEY recordID(recordID)
);


drop table realWord;
create table realWord (
  ID                  int not null default 0 auto_increment, 
  caption             varchar(30) not null default '', 
  soundex             varchar(10) not null default '', 
  language            char(2) not null default '', 
  popularity          smallint not null default 0, 
  len                 tinyint not null default 0,
  origUseCount        int not null default 0, 
  siteUseCount        int not null default 0, 
  searchCount         int not null default 0, 
  primary key (ID), 
  unique (caption), 
  key(caption), 
  key(soundex), 
  key(language)
);

drop table word2record;
create table word2record (
  ID                  int not null default 0 auto_increment, 
  realWordID          int not null default 0, 
  dbName              varchar(50) not null default '', 
  dbTable             varchar(50) not null default '', 
  record_id           int not null default 0, 
  ranking             smallint not null default 0, 
  primary key (ID), 
  key(realWordID), 
  key dbNameTableRecord (dbName, dbTable, record_id), 
  key(record_id), 
  key(ranking)
);

drop table word;
create table word (
  ID                  int not null default 0 auto_increment, 
  caption             varchar(30) not null default '', 
  origWord            varchar(30) not null default '', 
  searchCount         int not null default 0, 
  useCount            int not null default 0, 
  primary key (ID), 
  key(caption)
);

FLUSH PRIVILEGES;
