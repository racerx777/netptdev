<?php/********************************************************************************************
* BlueShoes Framework; This file is part of the php application framework.
* NOTE: This code is stripped (obfuscated). To get the clean documented code goto 
*       www.blueshoes.org and register for the free open source *DEVELOPER* version or 
*       buy the commercial version.
*       
*       In case you've already got the developer version, then this is one of the few 
*       packages/classes that is only available to *PAYING* customers.
*       To get it go to www.blueshoes.org and buy a commercial version.
* 
* @copyright www.blueshoes.org
* @author    Samuel Blume <sam at blueshoes dot org>
* @author    Andrej Arn <andrej at blueshoes dot org>
*/?><?php
require_once($APP['path']['core'] . 'db/Bs_MySql.class.php');$GLOBALS[strToLower('Bs_Mysql_PhpUnit')] = 'object';class Bs_Mysql_PhpUnit extends Bs_TestCase {var $_Bs_MySql;var $_APP;var $_dsn;var $_res;   var $_field; function Bs_MySql_PhpUnit($name) {$this->Bs_TestCase($name);$this->_APP      = &$GLOBALS['APP'];$this->_Bs_MySql = new Bs_MySql();}
function setUp() {if (!isSet($this->_APP['db']['ecg'])) {$this->_APP['db']['ecg'] = array();$this->_APP['db']['ecg']['name']      = 'bs_ecg';$this->_APP['db']['ecg']['host']      = 'localhost';$this->_APP['db']['ecg']['port']      = '3306';$this->_APP['db']['ecg']['user']      = 'root';$this->_APP['db']['ecg']['pass']      = '';$this->_APP['db']['ecg']['socket']    = '';$this->_APP['db']['ecg']['syntax']    = 'mysql';$this->_APP['db']['ecg']['type']      = 'mysql';}
$this->_dsn      = $this->_APP['db']['ecg'];$status = $this->_Bs_MySql->connect($this->_dsn, FALSE);if (isEx($status)) {echo '<hr><font color="red"><b>';echo "we need a connection to the db to test a few things. <br>";echo "the global.conf.php-dist has a definition \$APP['db']['ecg'] for that. if you have removed it in your conf file, re-add it. the user needs the permission to create databases and tables.<br>";echo "when there is no such definition, the user 'root' with empty password is tried.<br>";echo 'the stackdump follows:';echo '</b></font><hr>';$status->stackDump('die');}
$this->_Bs_MySql->write("drop database IF EXISTS bs_ecg");$this->_Bs_MySql->write("create database IF NOT EXISTS bs_ecg");$this->_Bs_MySql->write("create table bs_ecg.test (
ID int not null default 0 auto_increment, 
field1 varchar(20) not null default '', 
field2 char(20)    not null default '', 
field3 int         not null default 0, 
primary key ID (ID)
)");$this->_Bs_MySql->write("insert into bs_ecg.test set field1='hello', field2='world',  field3='15'");$this->_Bs_MySql->write("insert into bs_ecg.test set field1='foo',   field2='bar',    field3='23'");$this->_Bs_MySql->write("insert into bs_ecg.test set field1='test',  field2='record', field3='249'");$t = $this->_Bs_MySql->write("create table bs_ecg.allFieldTypes (
ID int not null default 0 auto_increment, 
f_tinyint    TINYINT   not null default 0, 
f_smallint   SMALLINT  not null default 0, 
f_mediumint  MEDIUMINT not null default 0, 
f_int        INT       not null default 0, 
f_bigint     BIGINT    not null default 0, 
f_float      FLOAT     not null default 0, 
f_double     DOUBLE    not null default 0, 
f_decimal    DECIMAL   not null default 0, 
f_date       DATE      not null, 
f_datetime   DATETIME  not null, 
f_timestamp  TIMESTAMP not null, 
f_year       YEAR      not null, 
f_char       CHAR(10)     not null default '', 
f_varchar    VARCHAR(20)  not null default '', 
f_tinyblob   TINYBLOB  not null default '', 
f_blob       BLOB      not null default '', 
f_mediumblob MEDIUMBLOB not null default '', 
f_longblob   LONGBLOB   not null default '', 
f_enum       ENUM('true','false') not null default 'false', 
f_set        SET('green','blue','red') not null default '', 
primary key ID (ID)
)");$this->_Bs_MySql->write("create table bs_ecg.Bank (
ID int not null default 0 auto_increment, 
OwnerID int not null default 0, 
amount int not null default 0, 
primary key ID (ID)
)");$this->_Bs_MySql->write("create table bs_ecg.Owner (
ID int not null default 0 auto_increment, 
caption varchar(20) not null default '', 
primary key ID (ID)
)");$this->_Bs_MySql->write("insert into bs_ecg.Owner set caption='Dora'");$this->_Bs_MySql->write("insert into bs_ecg.Owner set caption='Bob'");$this->_Bs_MySql->write("insert into bs_ecg.Owner set caption='Alfred'");$this->_Bs_MySql->write("insert into bs_ecg.Owner set caption='Claudio'");$this->_Bs_MySql->write("insert into bs_ecg.Bank set OwnerID=1, amount=1000");$this->_Bs_MySql->write("insert into bs_ecg.Bank set OwnerID=2, amount=8000");$this->_Bs_MySql->write("insert into bs_ecg.Bank set OwnerID=3, amount=3000");$this->_Bs_MySql->write("insert into bs_ecg.Bank set OwnerID=4, amount=7000");$this->_Bs_MySql->disconnect();}
function runTest() {$this->__Bs_MySql_connect();$this->__Bs_MySql_disconnect();$this->__Bs_MySql_connect2();$this->__Bs_MySql_disconnect2();$this->__Bs_MySql_selectDb();$this->__Bs_MySql_selectDb2();$this->__Bs_MySql_read();$this->__Bs_MySql_fetchRow();$this->__Bs_MySql_numCols();$this->__Bs_MySql_numCols2();$this->__Bs_MySql_numRows();$this->__Bs_MySql_numRows2();$this->__Bs_MySql_affectedRows();$this->__Bs_MySql_affectedRows2();$this->__Bs_MySql_insertId();$this->__Bs_MySql_fieldName();$this->__Bs_MySql_fieldName2();$this->__Bs_MySql_fieldName3();$this->__Bs_MySql_fieldName4();$this->__Bs_MySql_tableName();$this->__Bs_MySql_tableName2();$this->__Bs_MySql_databaseName();$this->__Bs_MySql_fieldLen();$this->__Bs_MySql_fieldFlags();$this->__Bs_MySql_fieldFlags2();$this->__Bs_MySql_fieldFlags3();$this->__Bs_MySql_hasFieldFlag();$this->__Bs_MySql_hasFieldFlag2();$this->__Bs_MySql_hasFieldFlag3();$this->__Bs_MySql_fieldType();$this->__Bs_MySql_fetchField();$this->__Bs_MySql_fetchField2();$this->__Bs_MySql_fetchField3();$this->__Bs_MySql_listFields();$this->__Bs_MySql_listTables();$this->__Bs_MySql_listDatabases();$this->__Bs_MySql_fetchFieldNames();$this->__Bs_MySql_fetchFieldNames2();$this->__Bs_MySql_fetchTableNames();$this->__Bs_MySql_fetchDatabaseNames();$this->__Bs_MySql_fieldExists();$this->__Bs_MySql_tableExists();$this->__Bs_MySql_databaseExists();$this->__Bs_MySql_getDbStructure();$this->__Bs_MySql_getTableProperties();$this->__Bs_MySql_getTableInfo();$this->__Bs_MySql_getIniVar();$this->__Bs_MySql_getStatusVar();$this->__Bs_MySql_getOpenTables();$this->__Bs_MySql_isReservedWord();$this->__Bs_MySql_subSelect();}
function __Bs_MySql_connect() {$expected = 'integer';$actual = $this->_Bs_MySql->connect($this->_dsn, FALSE);$this->assertEqualsType($expected, $actual, '__Bs_MySql_connect');}
function __Bs_MySql_disconnect() {$expected = TRUE;$actual = $this->_Bs_MySql->disconnect();$this->assertEquals($expected, $actual, '__Bs_MySql_disconnect');}
function __Bs_MySql_connect2() {$expected = 'integer';$actual = $this->_Bs_MySql->connect($this->_dsn);$this->assertEqualsType($expected, $actual, '__Bs_MySql_connect');}
function __Bs_MySql_disconnect2() {$expected = FALSE;$actual = $this->_Bs_MySql->disconnect();$this->assertEquals($expected, $actual, '__Bs_MySql_disconnect2');}
function __Bs_MySql_selectDb() {$expected = TRUE;$actual = $this->_Bs_MySql->selectDb('bs_ecg');$this->assertEquals($expected, $actual, '__Bs_MySql_selectDb');}
function __Bs_MySql_selectDb2() {$expected = FALSE;$actual = $this->_Bs_MySql->selectDb('asdfghjkl1234567890');$this->assertEquals($expected, $actual, '__Bs_MySql_selectDb2');}
function __Bs_MySql_read() {$expected = 'resource';$actual = $this->_res = $this->_Bs_MySql->read('select * from test');$this->assertEqualsType($expected, $actual, '__Bs_MySql_read');}
function __Bs_MySql_fetchRow() {$expected = 'array';$actual = $this->_Bs_MySql->fetchRow($this->_res);$this->assertEqualsType($expected, $actual, '__Bs_MySql_fetchRow');}
function __Bs_MySql_numCols() {$expected = 'integer';$actual = $this->_Bs_MySql->numCols($this->_res);$this->assertEqualsType($expected, $actual, '__Bs_MySql_numCols');}
function __Bs_MySql_numCols2() {$expected = 'bs_exception';$actual = $this->_Bs_MySql->numCols('someshit');$this->assertInstanceOf($expected, $actual, '__Bs_MySql_numCols2');}
function __Bs_MySql_numRows() {$expected = 'integer';$actual = $this->_Bs_MySql->numRows($this->_res);$this->assertEqualsType($expected, $actual, '__Bs_MySql_numRows');}
function __Bs_MySql_numRows2() {$expected = 'bs_exception';$actual = $this->_Bs_MySql->numRows('someshit');$this->assertInstanceOf($expected, $actual, '__Bs_MySql_numRows2');}
function __Bs_MySql_affectedRows() {$dev0 = $this->_Bs_MySql->write("update test set field3='15' where field3 = '15'");$expected = 0;$actual = $this->_Bs_MySql->affectedRows();$this->assertEquals($expected, $actual, '__Bs_MySql_affectedRows');}
function __Bs_MySql_affectedRows2() {$dev0 = $this->_Bs_MySql->write("update test set field3='15' where ID IN (1,2)");$expected = 1;$actual = $this->_Bs_MySql->affectedRows();$this->assertEquals($expected, $actual, '__Bs_MySql_affectedRows2');}
function __Bs_MySql_insertId() {$dev0 = $this->_Bs_MySql->write("insert into test set field1='new record'");$expected = 4;$actual = $this->_Bs_MySql->insertId();$this->assertEquals($expected, $actual, '__Bs_MySql_insertId');}
function __Bs_MySql_fieldName() {$expected = 'bs_exception';$actual = $this->_Bs_MySql->fieldName('someshit', 'someshit');$this->assertInstanceOf($expected, $actual, '__Bs_MySql_fieldName');}
function __Bs_MySql_fieldName2() {$expected = 'ID';$actual = $this->_Bs_MySql->fieldName($this->_res, 0);$this->assertEquals($expected, $actual, '__Bs_MySql_fieldName2');}
function __Bs_MySql_fieldName3() {$expected = 'bs_exception';$actual = $this->_Bs_MySql->fieldName($this->_res, 15);$this->assertInstanceOf($expected, $actual, '__Bs_MySql_fieldName3');}
function __Bs_MySql_fieldName4() {$res = $this->_Bs_MySql->read("select test.iD, field1 as field2, field2 as shit from test");$expected = 'iD,field2,shit';$actual[] = $this->_Bs_MySql->fieldName($res, 0);$actual[] = $this->_Bs_MySql->fieldName($res, 1);$actual[] = $this->_Bs_MySql->fieldName($res, 2);$actual = join(',', $actual);$this->assertEquals($expected, $actual, '__Bs_MySql_fieldName4');}
function __Bs_MySql_tableName() {$res = $this->_Bs_MySql->read("select test.iD, field1 as field2, field2 as shit from test");$expected = 'test';$actual = $this->_Bs_MySql->tableName($res, 1);$this->assertEquals($expected, $actual, '__Bs_MySql_tableName');}
function __Bs_MySql_tableName2() {$x = @mysql_list_tables('bs_ecg');$expected = 'allfieldtypes';$actual = $this->_Bs_MySql->tableName2($x, 0);$this->assertEquals($expected, $actual, '__Bs_MySql_tableName2');}
function __Bs_MySql_databaseName() {$x = @mysql_list_dbs();$expected = 'string';$actual = $this->_Bs_MySql->databaseName($x, 0);$this->assertEqualsType($expected, $actual, '__Bs_MySql_databaseName');}
function __Bs_MySql_fieldLen() {$expected = 11;$actual = $this->_Bs_MySql->fieldLen($this->_res, 0);$this->assertEquals($expected, $actual, '__Bs_MySql_fieldLen');}
function __Bs_MySql_fieldFlags() {$expected = 'not_null primary_key auto_increment';$actual = $this->_Bs_MySql->fieldFlags($this->_res, 0);$this->assertEquals($expected, $actual, '__Bs_MySql_fieldFlags');}
function __Bs_MySql_fieldFlags2() {$expected = array('not_null', 'primary_key', 'auto_increment');$actual = $this->_Bs_MySql->fieldFlags($this->_res, 0, 'vector');$this->assertEquals($expected, $actual, '__Bs_MySql_fieldFlags2');}
function __Bs_MySql_fieldFlags3() {$expected = array('not_null'       => TRUE, 
'auto_increment' => TRUE, 
'primary_key'    => TRUE, 
'unique_key'     => FALSE, 
'multiple_key'   => FALSE, 
'unsigned'       => FALSE, 
'zerofill'       => FALSE, 
'binary'         => FALSE, 
'blob'           => FALSE, 'enum'           => FALSE, 'timestamp'      => FALSE  );$actual = $this->_Bs_MySql->fieldFlags($this->_res, 0, 'hash');$this->assertEquals($expected, $actual, '__Bs_MySql_fieldFlags3 now as this test failed, this pretty much means that you need to update the code of Bs_MySql->fieldFlags().');}
function __Bs_MySql_hasFieldFlag() {$expected = NULL;$actual = $this->_Bs_MySql->hasFieldFlag($this->_res, 0, 'lalalala');$this->assertEquals($expected, $actual, '__Bs_MySql_hasFieldFlag');}
function __Bs_MySql_hasFieldFlag2() {$expected = TRUE;$actual = $this->_Bs_MySql->hasFieldFlag($this->_res, 0, 'auto_increment');$this->assertEquals($expected, $actual, '__Bs_MySql_hasFieldFlag2');}
function __Bs_MySql_hasFieldFlag3() {$expected = FALSE;$actual = $this->_Bs_MySql->hasFieldFlag($this->_res, 0, 'zerofill');$this->assertEquals($expected, $actual, '__Bs_MySql_hasFieldFlag3');}
function __Bs_MySql_fieldType() {$res = $this->_res = $this->_Bs_MySql->read('select * from allFieldTypes');$expected = 'int';$actual = $this->_Bs_MySql->fieldType($res, 1);$this->assertEquals($expected, $actual, '__Bs_MySql_fieldType[1]');$expected = 'int';$actual = $this->_Bs_MySql->fieldType($res, 2);$this->assertEquals($expected, $actual, '__Bs_MySql_fieldType[2]');$expected = 'int';$actual = $this->_Bs_MySql->fieldType($res, 3);$this->assertEquals($expected, $actual, '__Bs_MySql_fieldType[3]');$expected = 'int';$actual = $this->_Bs_MySql->fieldType($res, 4);$this->assertEquals($expected, $actual, '__Bs_MySql_fieldType[4]');$expected = 'int';$actual = $this->_Bs_MySql->fieldType($res, 5);$this->assertEquals($expected, $actual, '__Bs_MySql_fieldType[5]');$expected = 'real';$actual = $this->_Bs_MySql->fieldType($res, 6);$this->assertEquals($expected, $actual, '__Bs_MySql_fieldType[6]');$expected = 'real';$actual = $this->_Bs_MySql->fieldType($res, 7);$this->assertEquals($expected, $actual, '__Bs_MySql_fieldType[7]');$expected = 'real';$actual = $this->_Bs_MySql->fieldType($res, 8);$this->assertEquals($expected, $actual, '__Bs_MySql_fieldType[8]');$expected = 'date';$actual = $this->_Bs_MySql->fieldType($res, 9);$this->assertEquals($expected, $actual, '__Bs_MySql_fieldType[9]');$expected = 'datetime';$actual = $this->_Bs_MySql->fieldType($res, 10);$this->assertEquals($expected, $actual, '__Bs_MySql_fieldType[10]');$expected = 'timestamp';$actual = $this->_Bs_MySql->fieldType($res, 11);$this->assertEquals($expected, $actual, '__Bs_MySql_fieldType[11]');$expected = 'year';$actual = $this->_Bs_MySql->fieldType($res, 12);$this->assertEquals($expected, $actual, '__Bs_MySql_fieldType[12]');$expected = 'string';$actual = $this->_Bs_MySql->fieldType($res, 13);$this->assertEquals($expected, $actual, '__Bs_MySql_fieldType[13]');$expected = 'string';$actual = $this->_Bs_MySql->fieldType($res, 14);$this->assertEquals($expected, $actual, '__Bs_MySql_fieldType[14]');$expected = 'blob';$actual = $this->_Bs_MySql->fieldType($res, 15);$this->assertEquals($expected, $actual, '__Bs_MySql_fieldType[15]');$expected = 'blob';$actual = $this->_Bs_MySql->fieldType($res, 16);$this->assertEquals($expected, $actual, '__Bs_MySql_fieldType[16]');$expected = 'blob';$actual = $this->_Bs_MySql->fieldType($res, 17);$this->assertEquals($expected, $actual, '__Bs_MySql_fieldType[17]');$expected = 'blob';$actual = $this->_Bs_MySql->fieldType($res, 18);$this->assertEquals($expected, $actual, '__Bs_MySql_fieldType[18]');$expected = 'string';$actual = $this->_Bs_MySql->fieldType($res, 19);$this->assertEquals($expected, $actual, '__Bs_MySql_fieldType[19]');$expected = 'string';$actual = $this->_Bs_MySql->fieldType($res, 20);$this->assertEquals($expected, $actual, '__Bs_MySql_fieldType[20]');}
function __Bs_MySql_fetchField() {$expected = 'bs_exception';$actual = $this->_field = $this->_Bs_MySql->fetchField($this->_res, 100);$this->assertInstanceOf($expected, $actual, "__Bs_MySql_fetchField");}
function __Bs_MySql_fetchField2() {$expected = 'object';$actual = $this->_field = $this->_Bs_MySql->fetchField($this->_res, 0);$this->assertEqualsType($expected, $actual, "__Bs_MySql_fetchField2");}
function __Bs_MySql_fetchField3() {$meta = $this->_field = $this->_Bs_MySql->fetchField($this->_res, 1);$expected = 'f_tinyint';$actual   = $meta->name;$this->assertEquals($expected, $actual, "__Bs_MySql_fetchField3(), property name");$expected = 'allFieldTypes';$actual   = $meta->table;$this->assertEquals($expected, $actual, "__Bs_MySql_fetchField3(), property table");$expected = 0;$actual   = $meta->max_length;$this->assertEquals($expected, $actual, "__Bs_MySql_fetchField3(), property max_length");$expected = 1;$actual   = $meta->not_null;$this->assertEquals($expected, $actual, "__Bs_MySql_fetchField3(), property not_null");$expected = 0;$actual   = $meta->primary_key;$this->assertEquals($expected, $actual, "__Bs_MySql_fetchField3(), property primary_key");$expected = 0;$actual   = $meta->multiple_key;$this->assertEquals($expected, $actual, "__Bs_MySql_fetchField3(), property multiple_key");$expected = 1;$actual   = $meta->numeric;$this->assertEquals($expected, $actual, "__Bs_MySql_fetchField3(), property numeric");$expected = 0;$actual   = $meta->blob;$this->assertEquals($expected, $actual, "__Bs_MySql_fetchField3(), property blob");$expected = 'int';$actual   = $meta->type;$this->assertEquals($expected, $actual, "__Bs_MySql_fetchField3(), property type");$expected = 0;$actual   = $meta->unsigned;$this->assertEquals($expected, $actual, "__Bs_MySql_fetchField3(), property unsigned");$expected = 0;$actual   = $meta->zerofill;$this->assertEquals($expected, $actual, "__Bs_MySql_fetchField3(), property zerofill");}
function __Bs_MySql_listFields() {$expected = 'resource';$actual = $this->_field = $this->_Bs_MySql->listFields('test');$this->assertEqualsType($expected, $actual, "__Bs_MySql_listFields");}
function __Bs_MySql_listTables() {$expected = 'resource';$actual = $this->_field = $this->_Bs_MySql->listTables();$this->assertEqualsType($expected, $actual, "__Bs_MySql_listTables");}
function __Bs_MySql_listDatabases() {$expected = 'resource';$actual = $this->_field = $this->_Bs_MySql->listDatabases();$this->assertEqualsType($expected, $actual, "__Bs_MySql_listDatabases");}
function __Bs_MySql_fetchFieldNames() {$expected = array('ID', 'field1', 'field2', 'field3');$actual = $this->_field = $this->_Bs_MySql->fetchFieldNames('test');$this->assertEquals($expected, $actual, "__Bs_MySql_fetchFieldNames");}
function __Bs_MySql_fetchFieldNames2() {$expected = 'ID, field1, field2, field3';$actual = $this->_field = $this->_Bs_MySql->fetchFieldNames('test', NULL, 'string');$this->assertEquals($expected, $actual, "__Bs_MySql_fetchFieldNames2");}
function __Bs_MySql_fetchTableNames() {$expected = 'array';$actual = $this->_field = $this->_Bs_MySql->fetchTableNames();$this->assertEqualsType($expected, $actual, "__Bs_MySql_fetchTableNames");$expected = 'string';$actual = $this->_field = $this->_Bs_MySql->fetchTableNames(NULL, 'string');$this->assertEqualsType($expected, $actual, "__Bs_MySql_fetchTableNames");}
function __Bs_MySql_fetchDatabaseNames() {$expected = 'array';$actual = $this->_field = $this->_Bs_MySql->fetchDatabaseNames();$this->assertEqualsType($expected, $actual, "__Bs_MySql_fetchDatabaseNames");$expected = 'string';$actual = $this->_field = $this->_Bs_MySql->fetchDatabaseNames(NULL, 'string');$this->assertEqualsType($expected, $actual, "__Bs_MySql_fetchDatabaseNames");}
function __Bs_MySql_fieldExists() {$expected = TRUE;$actual = $this->_Bs_MySql->fieldExists('ID', 'test');$this->assertEquals($expected, $actual, "__Bs_MySql_fieldExists");}
function __Bs_MySql_tableExists() {$expected = TRUE;$actual = $this->_Bs_MySql->tableExists('allFieldTypes');$this->assertEquals($expected, $actual, "__Bs_MySql_tableExists");}
function __Bs_MySql_databaseExists() {$expected = TRUE;$actual = $this->_Bs_MySql->databaseExists('bs_ecg');$this->assertEquals($expected, $actual, "__Bs_MySql_databaseExists");}
function __Bs_MySql_getIniVar() {$expected = 'array';$actual   = $this->_Bs_MySql->getIniVar();$this->assertEqualsType($expected, $actual, "__Bs_MySql_getIniVar");}
function __Bs_MySql_getStatusVar() {$expected = 'array';$actual   = $this->_Bs_MySql->getStatusVar();$this->assertEqualsType($expected, $actual, "__Bs_MySql_getStatusVar");}
function __Bs_MySql_getOpenTables() {$expected = 'array';$actual   = $this->_Bs_MySql->getOpenTables();$this->assertEqualsType($expected, $actual, "__Bs_MySql_getOpenTables");$expected = 'array';$actual   = $this->_Bs_MySql->getOpenTables(NULL, 'extended');$this->assertEqualsType($expected, $actual, "__Bs_MySql_getOpenTables");$expected = 'string';$actual   = $this->_Bs_MySql->getOpenTables(NULL, 'string');$this->assertEqualsType($expected, $actual, "__Bs_MySql_getOpenTables");}
function __Bs_MySql_getDbStructure() {$expected = array();$expected['test'] = array('ID', 'field1', 'field2', 'field3');$expected['allfieldtypes'] = array('ID', 
'f_tinyint',
'f_smallint',
'f_mediumint',
'f_int',
'f_bigint',
'f_float',
'f_double',
'f_decimal',
'f_date',
'f_datetime',
'f_timestamp',
'f_year',
'f_char',
'f_varchar',
'f_tinyblob',
'f_blob',
'f_mediumblob',
'f_longblob',
'f_enum',
'f_set'
);$tmp = $this->_Bs_MySql->getDbStructure();$actual['test'] = $tmp['test'];$actual['allfieldtypes'] = $tmp['allfieldtypes'];$this->assertEquals($expected, $actual, "__Bs_MySql_getDbStructure");}
function __Bs_MySql_getTableProperties() {$expected = 'array';$actual   = $this->_Bs_MySql->getTableProperties('test');$this->assertEqualsType($expected, $actual, "__Bs_MySql_getTableProperties");}
function __Bs_MySql_getTableInfo() {$expected = 'array';$actual   = $this->_Bs_MySql->getTableInfo('test');$this->assertEqualsType($expected, $actual, "__Bs_MySql_getTableInfo");}
function __Bs_MySql_isReservedWord() {$expected = 0;$actual   = $this->_Bs_MySql->isReservedWord('lalala');$this->assertEquals($expected, $actual, "__Bs_MySql_isReservedWord");$expected = 1;$actual   = $this->_Bs_MySql->isReservedWord('timestamP');$this->assertEquals($expected, $actual, "__Bs_MySql_isReservedWord");$expected = 2;$actual   = $this->_Bs_MySql->isReservedWord('dayofweeK');$this->assertEquals($expected, $actual, "__Bs_MySql_isReservedWord");}
function __Bs_MySql_subSelect() {$query = "SELECT caption FROM bs_ecg.Owner WHERE Owner.ID IN (SELECT OwnerID FROM bs_ecg.Bank WHERE Bank.amount>5000 ORDER BY Bank.amount) ORDER BY Owner.caption";$resultSet = $this->_Bs_MySql->subSelect($query);$expected = '';while ($data = $this->_Bs_MySql->fetchRow($resultSet)) {if (isEx($data)) {$data->stackDump('echo');break;}
$expected .= $data['caption'] . ',';}
$actual = 'Bob,Claudio,';$this->assertEquals($expected, $actual, '__Bs_MySql_subSelect');}
}
?>