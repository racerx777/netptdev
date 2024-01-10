<?php
/*
* there is a HOWTO for this example: Bs_SimpleObjPersister.howTo.pdf
*/

class Foo {
  var $ID = 0; // Following attribute will be our ID (primary key)

  var $attr_1 = 'A string to persist';  // persist (string)
  var $attr_2 = 0;                      // persist (integer)

  var $dummy = '';                      // don't persist
  
  function bs_sop_getHints() {
    static $hint_hash = array (
        'primary' => array (
           'ID' => array('name'=>'id', 'type'=>'auto_increment'),
          ),
        'fields' => array (
           'attr_1' => array('name'=>'str', 'metaType'=>'string', 'size'=>40 ), //of course it would make sense to use 
           'attr_2' => array('name'=>'num', 'metaType'=>'integer'),             //the var names as field names too.
          )
       );
     return $hint_hash;
  }
}


//-----------------------------------------------------------------------------------
// A) First thing we do here, is to include the DB-Factory that will return us a 
//    DB-Agent. The Bs_SimpleObjPersister needs a DB-Agent to 'talk' to the 
//    underlying DB.
require_once($_SERVER['DOCUMENT_ROOT'] . '../global.conf.php');
require_once($GLOBALS['APP']['path']['core'] . 'db/Bs_Db.class.php');

//-----------------------------------------------------------------------------------
// B) Then setup the connection parameters, that we want to pass to the DB-Factory. 
//    In this case it's going to return a mySQL DB-Agent
$dsn = array ('name'=>'test', 'host'=>'localhost', 'port'=>'3306', 'socket'=>'',
              'user'=>'root', 'pass'=>'',
              'syntax'=>'mysql', 'type'=>'mysql');

//-----------------------------------------------------------------------------------
// C) Get the DB-Agent. If the return is a Bs_Exception echo error and die.
if (isEx($dbAgent =& getDbObject($dsn))) {
  $dbAgent->stackDump('echo');
  die();
}

//-----------------------------------------------------------------------------------
// D) Now create the Bs_SimpleObjPersister and pass the DB-Agent. It's now ready 
//    to be used ...
require_once($GLOBALS['APP']['path']['core'] . 'storage/objectpersister/Bs_SimpleObjPersister.class.php');

$objPersister = new Bs_SimpleObjPersister();
$objPersister->setDbObject($dbAgent);

//let's store a new one
$myFoo = new Foo();
$myFoo->attr_1 = 'This is new!';
$objPersister->store($myFoo);
$myNewId = $myFoo->ID;

//and now let's load it
$myFoo = new Foo();
$myFoo->ID = $myNewId;
if ($objPersister->load($myFoo)) {
  echo $myFoo->attr_1 . ' ' . $myFoo->ID;  // Will print 'This is new!' (plus the ID);
}
?>