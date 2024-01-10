<?php
# example 2:
# Use with some property settings.
# This time there is no 'origin-file' file and we use a 'lifetime' 
# for the cache.


#include dependencies
require_once($_SERVER["DOCUMENT_ROOT"]    . "../global.conf.php");
include_once($APP['path']['core'] . 'file/Bs_FileCache.class.php');

# 1) create the instance and set properties
$cacher =& new Bs_FileCache();
$cacher->setDir('r:/');               // hint: Use a RAM-Disk!  hint 2: use getTmp() it returns the temp folder of your operating system.
$cacher->setBufferSize('200k');       // % of memory is also possible
$cacher->setVerboseCacheNames(TRUE);  // Give cache-files a readable name

# 2) Try to get the cached data using any key *you* define.
$MY_CACHE_KEY = 'chacheKey_1';
$data = $cacher->fetch($MY_CACHE_KEY);

# 3) Test the return value
if ($data === FALSE) { // ERROR
  echo "ERROR: " . $cacher->getLastError();
} elseif ($data === NULL){ // cache is "Out Of Date", or not cached yet.
  // Crunch the data (Replaced with *YOUR* crunching function.)
  $crunchedData = myCruncher('/tmp/toCrunchData.txt');
  # 4) Cache the data serialized with a timeout of 600s 
	#    and disabled 'origin-file' check
  $cacher->store($MY_CACHE_KEY, serialize($crunchedData), $originCheck=FALSE, 600);
} else { // SUCCESS
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