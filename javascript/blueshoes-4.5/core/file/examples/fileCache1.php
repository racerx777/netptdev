<?php
# basic example:
# imagine that we have a source file, in our case /tmp/toCrunchData.txt
# based on that file we create something. and the output will be cached. 
# whenever the source file changes, we have to drop the cache and 
# "crunch" our data again.

#include dependencies
require_once($_SERVER['DOCUMENT_ROOT']    . '../global.conf.php');
include_once($APP['path']['core'] . 'file/Bs_FileCache.class.php');

# 1) create the instance
$cacher =& new Bs_FileCache();

# 2) Try to get the cached data using the path of the source file as key.
$data = $cacher->fetch('/tmp/toCrunchData.txt');

# 3) Test the return value
if ($data === FALSE) {       // ERROR
  echo "ERROR: " . $cacher->getLastError();
} elseif ($data === NULL){   // cache is "Out Of Date", or not cached yet.
  // Crunch the data (Replace with *YOUR* crunching function.)
  $crunchedData = myCruncher('/tmp/toCrunchData.txt');
	
  # 4) Cache the data as serialized data
  $cacher->store('/tmp/toCrunchData.txt', serialize($crunchedData));
} else {                     // SUCCESS
  $crunchedData = unserialize($data); 
}

# 5) do something with $crunchedData
dump($crunchedData);


# this is your cruncher function. of course you would do more 
# than just reading the data.
function myCruncher($fullPath) {
	return join('', file($fullPath));
}
?>