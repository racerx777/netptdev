<?php
/**
* @package    applications_websearchengine
* @subpackage examples
*/

require_once($_SERVER['DOCUMENT_ROOT'] . '../global.conf.php');
require_once($APP['path']['applications'] . 'websearchengine/Bs_Wse_WebSearchEngine.class.php');

ignore_user_abort(FALSE); //it says it already does it, but then it doesn't do it. hrm... :/

$profileName = 'phpsearch';

$wseProfile =& new Bs_Wse_Profile();
$status = $wseProfile->setDbByObj($GLOBALS['bsDb']);
if (isEx($status)) {
	$status->stackDump('die');
}
set_time_limit(7200); //2hours
//$wseProfile->drop($profileName);
$status = $wseProfile->load($profileName);
if (isEx($status) || ($status === FALSE)) {
	//most probably: tables don't exist yet.
	$status = $wseProfile->create($profileName, $wseXml='', $isXml='');
}
$status = $wseProfile->load($profileName);
if (isEx($status)) {
	$status->stackDump('die');
} elseif ($status === FALSE) {
	die('could not load profile.');
}

$wse =& new Bs_Wse_WebSearchEngine();
$wse->setProfile($wseProfile);
$walker = &$wse->getWalker($profileName);
//dump(indexPhpBuilderCom($wse, $walker, $wseProfile));
//dump(indexPhpMagDe($wse, $walker, $wseProfile));
//dump(indexPhpWeblogsCom($wse, $walker, $wseProfile));
//dump(indexPhpFreaksCom($wse, $walker, $wseProfile));
//dump(indexPhpCompleteCom($wse, $walker, $wseProfile));
//dump(indexBlueshoesOrg($wse, $walker, $wseProfile));
////dump(indexPhpjCom($wse, $walker, $wseProfile));
//dump(indexPhpAdvisoryCom($wse, $walker, $wseProfile));
////dump(indexPhpUgCh($wse, $walker, $wseProfile)); //sends session id's in the url if cookies disabled :/
//dump(indexPhpUgDe($wse, $walker, $wseProfile));
//dump(indexZendCom($wse, $walker, $wseProfile));
//dump(indexHotscriptsCom($wse, $walker, $wseProfile));
//dump(indexScriptSearchCom($wse, $walker, $wseProfile));
//dump(indexWeberdevCom($wse, $walker, $wseProfile)); //did not follow content :/ check allowIgnore settings
//dump(indexOnlampCom($wse, $walker, $wseProfile));
//dump(indexPxSklarCom($wse, $walker, $wseProfile));
//dump(indexPhpNet($wse, $walker, $wseProfile));
//dump(indexWebkreatorCom($wse, $walker, $wseProfile));
//dump(indexPhpClassesPhpStartDe($wse, $walker, $wseProfile));
//dump(indexDevShedCom($wse, $walker, $wseProfile));


function indexDevShedCom(&$wse, &$walker, &$profile) {
/*
update bs_wse_phpsearch_pages set changeFrequency = 100 where url = 'http://www.devshed.com/Server_Side/PHP/';
*/
	$domainName = 'www.devshed.com';
	$profile->limitDomains = array($domainName);
	$profile->waitAfterIndex = 2;
	$profile->allowIgnore = array(
		array('value'=>'/comments',               'type'=>'part', 'ignore'=>TRUE), 
		array('value'=>'/print',                  'type'=>'part', 'ignore'=>TRUE), 
		array('value'=>'/print_html',             'type'=>'part', 'ignore'=>TRUE), 
		array('value'=>'/Server_Side/PHP/',       'type'=>'part', 'ignore'=>FALSE), 
		array('value'=>'/',                       'type'=>'part', 'ignore'=>TRUE), 
	);
	
	$profile->categories = array(
		array('value'=>'/',          'type'=>'part', 'category'=>'articles'), 
	);
	//dump($walker->isIgnoredUrl('http://www.devshed.com/Server_Side/PHP/AmazonAPI/AmazonAPI1', FALSE)); exit;
	dump($walker->index('http://' . $domainName . '/Server_Side/PHP/', TRUE));
	echo '<h1>done!</h1>';
	echo $walker->stopWatch->toHtml();
}

function indexPhpClassesPhpStartDe(&$wse, &$walker, &$profile) {
	$domainName = 'phpclasses.php-start.de';
	$profile->limitDomains = array($domainName);
	$profile->waitAfterIndex = 2;
	$profile->allowIgnore = array(
		array('value'=>'/goto/',               'type'=>'part', 'ignore'=>TRUE), 
		array('value'=>'/buy/',                'type'=>'part', 'ignore'=>TRUE), 
		array('value'=>'/login.html',          'type'=>'part', 'ignore'=>TRUE), //part, not file! strange url.
		array('value'=>'/login/',              'type'=>'part', 'ignore'=>TRUE), //part, not file! strange url.
		array('value'=>'/subscribe.html',      'type'=>'part', 'ignore'=>TRUE), //part, not file! strange url.
		array('value'=>'/browse.html/author/', 'type'=>'part', 'ignore'=>TRUE), 
		array('value'=>'/browse.html/file/',   'type'=>'part', 'ignore'=>TRUE), 
	);
	
	$profile->categories = array(
		array('value'=>'/products.html',          'type'=>'part', 'category'=>'books'), //part, not file! strange url.
		array('value'=>'/browse.html/class/',     'type'=>'part', 'category'=>'code'), //part, not file! strange url.
		array('value'=>'/browse.html/package/',   'type'=>'part', 'category'=>'code'), //part, not file! strange url.
	);
	dump($walker->index('http://' . $domainName . '/', TRUE));
	echo '<h1>done!</h1>';
	echo $walker->stopWatch->toHtml();
}

function indexWebkreatorCom(&$wse, &$walker, &$profile) {
	//there is an url problem. i am on http://www.webkreator.com/php/ and it links to 
	//<a href="php/tools/"> and mozilla translates it to http://www.webkreator.com/php/tools/ which 
	//is correct but i translate to http://www.webkreator.com/php/php/tools/. why is that? no clue.
	//have to fix this.
	$domainName = 'www.webkreator.com';
	$profile->limitDomains = array($domainName);
	$profile->waitAfterIndex = 2;
	$profile->allowIgnore = array(
		array('value'=>'/php/',           'type'=>'part', 'ignore'=>FALSE), 
		array('value'=>'/',               'type'=>'part', 'ignore'=>TRUE), 
	);
	
	$profile->categories = array(
		array('value'=>'/php/books/',             'type'=>'part', 'category'=>'books'), 
		array('value'=>'/php/tools/',             'type'=>'part', 'category'=>'applications'), 
		array('value'=>'/php/xcs/',               'type'=>'part', 'category'=>'applications'), //some xml-rpc app
		array('value'=>'/php/techniques/',        'type'=>'part', 'category'=>'articles'), 
		array('value'=>'/php/configuration/',     'type'=>'part', 'category'=>'articles'), 
		array('value'=>'/php/concepts/',          'type'=>'part', 'category'=>'articles'), 
	);
	dump($walker->index('http://' . $domainName . '/php/', TRUE));
	echo '<h1>done!</h1>';
	echo $walker->stopWatch->toHtml();
}

function indexPhpNet(&$wse, &$walker, &$profile) {
/*
update bs_wse_phpsearch_pages set changeFrequency = 100 where url = 'http://www.php.net/manual/en/';
*/
	$domainName = 'www.php.net';
	$profile->limitDomains = array($domainName);
	$profile->waitAfterIndex = 5;
	$profile->allowIgnore = array(
		//other stuff:
		array('value'=>'add-note.php',      'type'=>'file', 'ignore'=>TRUE), 
		array('value'=>'about-notes.php',   'type'=>'file', 'ignore'=>TRUE), 
		//used as part of the path!:
		array('value'=>'.gz',               'type'=>'part', 'ignore'=>TRUE), 
		array('value'=>'.tar.gz',           'type'=>'part', 'ignore'=>TRUE), 
		array('value'=>'.zip',              'type'=>'part', 'ignore'=>TRUE), 
		array('value'=>'.bz2',              'type'=>'part', 'ignore'=>TRUE), 
		//"show source of current page" link:
		array('value'=>'source.php',        'type'=>'file', 'ignore'=>TRUE), 
		//seems to be broken, redirects to search page:
		array('value'=>'/stats/',           'type'=>'part', 'ignore'=>TRUE), 
		array('value'=>'ca.php',            'type'=>'file', 'ignore'=>TRUE), //500 events or so in the calendar, mostly old and junk.
		//docs are huge:
		array('value'=>'docs.php',          'type'=>'file', 'ignore'=>TRUE), 
		array('value'=>'download-docs.php', 'type'=>'file', 'ignore'=>TRUE), 
		array('value'=>'/manual/en/',       'type'=>'part', 'ignore'=>FALSE), //allow english so far
		array('value'=>'/manual/',          'type'=>'part', 'ignore'=>TRUE), 
	);
	$profile->categories = array(
		array('value'=>'books.php',               'type'=>'file', 'category'=>'books'), 
		array('value'=>'/manual/en/',             'type'=>'part', 'category'=>'manual'), 
	);
	$walker->_crawlUrlStack[] = 'http://www.php.net/books.php?type_lang=PHP_all';
	$walker->_crawlUrlStack[] = 'http://www.php.net/manual/en/language.variables.php';
	$walker->_crawlUrlStack[] = 'http://www.php.net/manual/en/';
	dump($walker->index('http://' . $domainName . '/', TRUE));
	echo '<h1>done!</h1>';
	echo $walker->stopWatch->toHtml();
}

function indexPxSklarCom(&$wse, &$walker, &$profile) {
	$domainName = 'px.sklar.com';
	$profile->limitDomains = array($domainName);
	$profile->waitAfterIndex = 2;
	$profile->allowIgnore = array(
		array('value'=>'&order=',           'type'=>'part', 'ignore'=>TRUE), 
		array('value'=>'_login=1',          'type'=>'part', 'ignore'=>TRUE), 
		array('value'=>'code.html',         'type'=>'file', 'ignore'=>TRUE), 
		array('value'=>'user.html',         'type'=>'file', 'ignore'=>TRUE), 
		array('value'=>'comments.html',     'type'=>'file', 'ignore'=>TRUE), 
		array('value'=>'section.html',      'type'=>'file', 'ignore'=>FALSE), 
	);
	//[?&]PHPSESSID=
	
	$profile->categories = array(
		array('value'=>'/',               'type'=>'part', 'category'=>'articles'), 
	);
	dump($walker->index('http://' . $domainName . '/', TRUE));
	echo '<h1>done!</h1>';
	echo $walker->stopWatch->toHtml();
}

function indexOnlampCom(&$wse, &$walker, &$profile) {
/*
update bs_wse_phpsearch_pages set changeFrequency = 100 where url = 'http://www.onlamp.com/php/';
*/
	$domainName = 'www.onlamp.com';
	$profile->limitDomains = array($domainName);
	$profile->waitAfterIndex = 2;
	$profile->allowIgnore = array(
		array('value'=>'/lpt/',           'type'=>'part', 'ignore'=>TRUE), //printer friendly page
		array('value'=>'mailto:',         'type'=>'part', 'ignore'=>TRUE), //mailto link
		array('value'=>'&x-order=',       'type'=>'part', 'ignore'=>TRUE), //user-comments in articles
		array('value'=>'&x-maxdepth=',    'type'=>'part', 'ignore'=>TRUE), //user-comments in articles
		array('value'=>'&x-showcontent=', 'type'=>'part', 'ignore'=>TRUE), //user-comments in articles
		array('value'=>'/php/',           'type'=>'part', 'ignore'=>FALSE), 
		array('value'=>'/',               'type'=>'part', 'ignore'=>TRUE), 
	);
	
	$profile->categories = array(
		array('value'=>'/',               'type'=>'part', 'category'=>'articles'), 
	);
	$url = 'http://' . $domainName . '/php/';
	//$url = 'http://' . $domainName . '/pub/a/php/2001/03/08/php_foundations.html';
	//dump($walker->isIgnoredUrl($url, FALSE)); exit;
	//$content = $walker->_httpClient->fetchPage($url);
	//$content = $walker->_httpClient->fetchPage($url);
	//dump($content); exit;
	//$hi =& new Bs_HtmlInfo();
	//$hi->initByString($content);
	//$links = $hi->fetchLinks(1);
	//dump($links); exit;
	dump($walker->index('http://' . $domainName . '/php/', TRUE));
	echo '<h1>done!</h1>';
	echo $walker->stopWatch->toHtml();
}

function indexWeberdevCom(&$wse, &$walker, &$profile) {
/*
update bs_wse_phpsearch_pages set changeFrequency = 100 where url = 'http://www.weberdev.com/';
update bs_wse_phpsearch_pages set changeFrequency = 100 where url = 'http://www.weberdev.com/maincat.php3?categoryID=106&category=PHP';
update bs_wse_phpsearch_pages set changeFrequency = 100 where url = 'http://www.weberdev.com/mainarticlescat.php3?cat=PHP&categoryID=106';
*/
	$domainName = 'www.weberdev.com';
	$profile->limitDomains = array($domainName);
	$profile->waitAfterIndex = 2;
	$profile->allowIgnore = array(
		array('value'=>'sort=title',      'type'=>'part', 'ignore'=>TRUE), 
		array('value'=>'sort=lname',      'type'=>'part', 'ignore'=>TRUE), 
		array('value'=>'sort=Iname',      'type'=>'part', 'ignore'=>TRUE), 
		array('value'=>'cat=PHP',         'type'=>'part', 'ignore'=>FALSE), 
		array('value'=>'category=PHP',    'type'=>'part', 'ignore'=>FALSE), 
		array('value'=>'secondary=PHP',   'type'=>'part', 'ignore'=>FALSE), 
		array('value'=>'/',               'type'=>'part', 'ignore'=>TRUE), 
	);
	
	$profile->categories = array(
		array('value'=>'/PHP/Books/',                'type'=>'part', 'category'=>'books'), 
		array('value'=>'/PHP/Articles/',             'type'=>'part', 'category'=>'articles'), 
		array('value'=>'/PHP/Magazine_Articles/',    'type'=>'part', 'category'=>'articles'), 
		array('value'=>'/PHP/Scripts_and_Programs/', 'type'=>'part', 'category'=>'code'), 
		array('value'=>'/PHP/Tips_and_Tutorials/',   'type'=>'part', 'category'=>'tutorials'), 
		//  /PHP/Online_Communities/
		//  /PHP/Web_Sites/
	);
	$walker->_crawlUrlStack[] = 'http://www.weberdev.com/maincat.php3?categoryID=106&category=PHP';
	$walker->_crawlUrlStack[] = 'http://www.weberdev.com/mainarticlescat.php3?cat=PHP&categoryID=106';
	dump($walker->index('http://' . $domainName . '/', TRUE));
	echo '<h1>done!</h1>';
	echo $walker->stopWatch->toHtml();
}

function indexScriptSearchCom(&$wse, &$walker, &$profile) {
	$domainName = 'www.scriptsearch.com';
	$profile->limitDomains = array($domainName);
	$profile->waitAfterIndex = 2;
	$profile->allowIgnore = array(
		array('value'=>'/PHP/',  'type'=>'part', 'ignore'=>FALSE), 
		array('value'=>'/',      'type'=>'part', 'ignore'=>TRUE), 
	);
	
	$profile->categories = array(
		array('value'=>'/PHP/Books/',                'type'=>'part', 'category'=>'books'), 
		array('value'=>'/PHP/Articles/',             'type'=>'part', 'category'=>'articles'), 
		array('value'=>'/PHP/Magazine_Articles/',    'type'=>'part', 'category'=>'articles'), 
		array('value'=>'/PHP/Scripts_and_Programs/', 'type'=>'part', 'category'=>'code'), 
		array('value'=>'/PHP/Tips_and_Tutorials/',   'type'=>'part', 'category'=>'tutorials'), 
		//  /PHP/Online_Communities/
		//  /PHP/Web_Sites/
	);
	dump($walker->index('http://' . $domainName . '/PHP/', TRUE));
	echo '<h1>done!</h1>';
	echo $walker->stopWatch->toHtml();
}

function indexHotscriptsCom(&$wse, &$walker, &$profile) {
	$domainName = 'www.hotscripts.com';
	$profile->limitDomains = array($domainName);
	$profile->waitAfterIndex = 2;
	$profile->allowIgnore = array(
		array('value'=>'/PHP/',  'type'=>'part', 'ignore'=>FALSE), 
		array('value'=>'/',      'type'=>'part', 'ignore'=>TRUE), 
	);
	
	$profile->categories = array(
		array('value'=>'/PHP/Books/',                'type'=>'part', 'category'=>'books'), 
		array('value'=>'/PHP/Software_and_Servers/', 'type'=>'part', 'category'=>'applications'), 
		array('value'=>'/PHP/Scripts_and_Programs/', 'type'=>'part', 'category'=>'code'), 
		array('value'=>'/PHP/Tips_and_Tutorials/',   'type'=>'part', 'category'=>'tutorials'), 
		array('value'=>'/PHP/Magazine_Articles/',    'type'=>'part', 'category'=>'articles'), 
		//  /PHP/Online_Communities/
		//  /PHP/Web_Sites/
	);
	dump($walker->index('http://' . $domainName . '/PHP/', TRUE));
	echo '<h1>done!</h1>';
	echo $walker->stopWatch->toHtml();
}

function indexZendCom(&$wse, &$walker, &$profile) {
/*
update bs_wse_phpsearch_pages set changeFrequency = 100 where url = 'http://www.zend.com/';
update bs_wse_phpsearch_pages set changeFrequency = 100 where url = 'http://www.zend.com/zend/tut/';
update bs_wse_phpsearch_pages set changeFrequency = 100 where url = 'http://www.zend.com/zend/spotlight/';
update bs_wse_phpsearch_pages set changeFrequency = 100 where url = 'http://www.zend.com/zend/trick/';
update bs_wse_phpsearch_pages set changeFrequency = 100 where url = 'http://www.zend.com/zend/art/';
update bs_wse_phpsearch_pages set changeFrequency = 100 where url = 'http://www.zend.com/jobs/';
update bs_wse_phpsearch_pages set changeFrequency = 100 where url = 'http://www.zend.com/talent/';
update bs_wse_phpsearch_pages set changeFrequency = 100 where url = 'http://www.zend.com/community.php';
update bs_wse_phpsearch_pages set changeFrequency = 100 where url = 'http://www.zend.com/zend/hof/';
update bs_wse_phpsearch_pages set changeFrequency = 100 where url = 'http://www.zend.com/store/';
update bs_wse_phpsearch_pages set changeFrequency = 100 where url = 'http://www.zend.com/partners/';
update bs_wse_phpsearch_pages set changeFrequency = 100 where url = 'http://www.zend.com/zend/week/';
update bs_wse_phpsearch_pages set changeFrequency = 100 where url = 'http://www.zend.com/publishers/';
update bs_wse_phpsearch_pages set changeFrequency = 100 where url = 'http://www.zend.com/';
update bs_wse_phpsearch_pages set changeFrequency = 100 where url = 'http://www.zend.com/';
update bs_wse_phpsearch_pages set changeFrequency = 100 where url = 'http://www.zend.com/';
*/
	$domainName = 'www.zend.com';
	$profile->limitDomains = array($domainName);
	$profile->waitAfterIndex     = 5;
	$profile->reindexIfUnchanged = 15;
	$profile->allowIgnore = array(
		array('value'=>'add_user.php',              'type'=>'file', 'ignore'=>TRUE), 
		array('value'=>'login.php',                 'type'=>'file', 'ignore'=>TRUE), 
		array('value'=>'email.php',                 'type'=>'file', 'ignore'=>TRUE), 
		array('value'=>'reg_form.php',              'type'=>'file', 'ignore'=>TRUE), 
		array('value'=>'go_to=',                    'type'=>'part', 'ignore'=>TRUE), 
		array('value'=>'print=1',                   'type'=>'part', 'ignore'=>TRUE), 
		array('value'=>'then_to=',                  'type'=>'part', 'ignore'=>TRUE), 
		array('value'=>'go_link.php',               'type'=>'file', 'ignore'=>TRUE), 
		array('value'=>'app_mail.php',              'type'=>'file', 'ignore'=>TRUE), 
		array('value'=>'download-php.php',          'type'=>'file', 'ignore'=>TRUE), 
		array('value'=>'api.php',                   'type'=>'file', 'ignore'=>TRUE), 
		array('value'=>'/manual/',                  'type'=>'part', 'ignore'=>TRUE), 
		array('value'=>'/apidoc/',                  'type'=>'part', 'ignore'=>TRUE), 
		array('value'=>'/phorum/',                  'type'=>'part', 'ignore'=>TRUE), 
		array('value'=>'/phpfunc/',                 'type'=>'part', 'ignore'=>TRUE), 
		array('value'=>'/codex.php?id=',            'type'=>'part', 'ignore'=>TRUE), //snippet without docu is not worth a lot.
		array('value'=>'/tips/tips.php?id=',        'type'=>'part', 'ignore'=>TRUE), //snippet without docu is not worth a lot.
		array('value'=>'/links/',                   'type'=>'part', 'ignore'=>TRUE), 
		array('value'=>'/hosting_sites/',           'type'=>'part', 'ignore'=>TRUE), 
		array('value'=>'show_comment.php',          'type'=>'file', 'ignore'=>TRUE), 
		array('value'=>'add_comment.php',           'type'=>'file', 'ignore'=>TRUE), 
		array('value'=>'/zend/cs/',                 'type'=>'part', 'ignore'=>TRUE), //case studies
		array('value'=>'/ads/',                     'type'=>'part', 'ignore'=>TRUE), 
		array('value'=>'getfreefile.php',           'type'=>'file', 'ignore'=>TRUE), 
		array('value'=>'&open=',                    'type'=>'part', 'ignore'=>TRUE), 
		
		array('value'=>'/tut/',                      'type'=>'part', 'ignore'=>FALSE), 
		array('value'=>'/zend/spotlight/',           'type'=>'part', 'ignore'=>FALSE), 
		array('value'=>'/zend/trick/',               'type'=>'part', 'ignore'=>FALSE), 
		array('value'=>'/art/',                      'type'=>'part', 'ignore'=>FALSE), 
		array('value'=>'/jobs/',                     'type'=>'part', 'ignore'=>FALSE), 
		array('value'=>'/talent/',                   'type'=>'part', 'ignore'=>FALSE), 
		array('value'=>'community.php',              'type'=>'file', 'ignore'=>FALSE), 
		array('value'=>'/zend/hof/',                 'type'=>'part', 'ignore'=>FALSE), 
		array('value'=>'/codex.php?CID=',            'type'=>'part', 'ignore'=>FALSE), 
		array('value'=>'/tips/tips.php?CID=',        'type'=>'part', 'ignore'=>FALSE), 
		array('value'=>'/apps.php?CID=',             'type'=>'part', 'ignore'=>FALSE), 
		array('value'=>'/store/',                    'type'=>'part', 'ignore'=>FALSE), 
		array('value'=>'/partners/',                 'type'=>'part', 'ignore'=>FALSE), 
		array('value'=>'show_zpal.php',              'type'=>'file', 'ignore'=>FALSE), 
		array('value'=>'/zend/week/',                'type'=>'part', 'ignore'=>FALSE), 
		array('value'=>'/publishers/',               'type'=>'part', 'ignore'=>FALSE), 
		array('value'=>'press.php',                  'type'=>'file', 'ignore'=>FALSE), 
	);
	
	$profile->categories = array(
		array('value'=>'/tut/',                      'type'=>'part', 'category'=>'tutorials'), 
		array('value'=>'/zend/spotlight/',           'type'=>'part', 'category'=>'tutorials'), 
		array('value'=>'/zend/trick/',               'type'=>'part', 'category'=>'tutorials'), 
		array('value'=>'/art/',                      'type'=>'part', 'category'=>'articles'), 
		array('value'=>'/jobs/',                     'type'=>'part', 'category'=>'jobs'), 
		array('value'=>'/talent/',                   'type'=>'part', 'category'=>'people'), 
		array('value'=>'community.php',              'type'=>'file', 'category'=>'people'), 
		array('value'=>'/zend/hof/',                 'type'=>'part', 'category'=>'people'), 
		array('value'=>'/codex.php?CID=',            'type'=>'part', 'category'=>'snippet'), 
		array('value'=>'/tips/tips.php?CID=',        'type'=>'part', 'category'=>'snippet'), 
		array('value'=>'/apps.php?CID=',             'type'=>'part', 'category'=>'applications'), 
		array('value'=>'/store/',                    'type'=>'part', 'category'=>'applications'), 
		array('value'=>'/partners/',                 'type'=>'part', 'category'=>'companies'), 
		array('value'=>'show_zpal.php',              'type'=>'file', 'category'=>'companies'), 
		array('value'=>'/zend/week/',                'type'=>'part', 'category'=>'news'), 
		array('value'=>'/publishers/',               'type'=>'part', 'category'=>'books'), 
	);
	dump($walker->index('http://' . $domainName . '/', TRUE));
	echo '<h1>done!</h1>';
	echo $walker->stopWatch->toHtml();
}

function indexPhpUgDe(&$wse, &$walker, &$profile) {
	$domainName = 'www.phpug.de';
	$profile->limitDomains = array($domainName);
	$profile->waitAfterIndex = 2;
	$profile->ignoreUrls = array(
		array('value'=>'/phpads/',              'type'=>'part'), 
	);
	$profile->allowUrls = array(
		//array('value'=>'/pdf/anleitungen/',                      'type'=>'part', 'category'=>'tutorials'), 
	);
	$profile->categories = array(
		//array('value'=>'/pdf/anleitungen/',                      'type'=>'part', 'category'=>'tutorials'), 
	);
	dump($walker->index('http://' . $domainName . '/', TRUE));
	echo '<h1>done!</h1>';
	echo $walker->stopWatch->toHtml();
}

function indexPhpUgCh(&$wse, &$walker, &$profile) {
	$domainName = 'phpug.ch';
	$profile->limitDomains = array($domainName);
	$profile->waitAfterIndex = 2;
	$profile->ignoreUrls = array(
		array('value'=>'/chat/',              'type'=>'part'), 
		array('value'=>'/mailingliste/',      'type'=>'part'), 
		array('value'=>'/phorum/',            'type'=>'part'), 
		array('value'=>'/phpAdsNew/',         'type'=>'part'), 
		array('value'=>'/phpAds/',            'type'=>'part'), 
		array('value'=>'mailtomember.php',    'type'=>'file'), 
		array('value'=>'/codereview/',        'type'=>'part'), 
		array('value'=>'change_currlang=',    'type'=>'part'), 
	);
	$profile->allowUrls = array(
		array('value'=>'/pdf/anleitungen/',                      'type'=>'part', 'category'=>'tutorials'), 
		array('value'=>'show_member.php',                        'type'=>'file', 'category'=>'people'), 
		array('value'=>'/news/',                                 'type'=>'part', 'category'=>'news'), 
		array('value'=>'/scripts/',                              'type'=>'part', 'category'=>'code'), 
		array('value'=>'/members/companies/?action=show_detail', 'type'=>'part', 'category'=>'companies'), 
	);
	$profile->categories = array(
		array('value'=>'/pdf/anleitungen/',                      'type'=>'part', 'category'=>'tutorials'), 
		array('value'=>'show_member.php',                        'type'=>'file', 'category'=>'people'), 
		array('value'=>'/news/',                                 'type'=>'part', 'category'=>'news'), 
		array('value'=>'/scripts/',                              'type'=>'part', 'category'=>'code'), 
		array('value'=>'/members/companies/?action=show_detail', 'type'=>'part', 'category'=>'companies'), 
	);
	dump($walker->index('http://' . $domainName . '/', TRUE));
	echo '<h1>done!</h1>';
	echo $walker->stopWatch->toHtml();
}

function indexPhpAdvisoryCom(&$wse, &$walker, &$profile) {
	$domainName = 'www.phpadvisory.com';
	$profile->limitDomains = array($domainName);
	$profile->waitAfterIndex = 2;
	$profile->ignoreUrls = array(
		array('value'=>'/newsletter/',            'type'=>'part'), 
		array('value'=>'/forums/',                'type'=>'part'), 
	);
	$profile->allowUrls = array(
		array('value'=>'/advisories/',     'type'=>'part', 'category'=>'advisories'), 
		array('value'=>'/articles/',       'type'=>'part', 'category'=>'articles'), 
	);
	$profile->categories = array(
		array('value'=>'/advisories/',     'type'=>'part', 'category'=>'advisories'), 
		array('value'=>'/articles/',       'type'=>'part', 'category'=>'articles'), 
	);
	dump($walker->index('http://' . $domainName . '/', TRUE));
	echo '<h1>done!</h1>';
	echo $walker->stopWatch->toHtml();
}

/*
function indexPhpjCom(&$wse, &$walker, &$profile) {
	$domainName = 'phpj.com';
	$profile->limitDomains = array($domainName);
	$profile->waitAfterIndex = 2;
	$profile->ignoreUrls = array(
		//array('value'=>'/ads/',            'type'=>'part'), 
	);
	$profile->allowUrls = array(
		array('value'=>'/tutorials.php',     'type'=>'part', 'category'=>'tutorials'), 
		array('value'=>'/users.php',         'type'=>'part', 'category'=>'people'), 
		array('value'=>'/devblogs.php',      'type'=>'part', 'category'=>'news'), 
		array('value'=>'/articles.php',      'type'=>'part', 'category'=>'news'), 
	);
	$profile->categories = array(
		array('value'=>'/tutorials.php',     'type'=>'part', 'category'=>'tutorials'), 
		array('value'=>'/users.php',         'type'=>'part', 'category'=>'people'), 
		array('value'=>'/devblogs.php',      'type'=>'part', 'category'=>'news'), 
		array('value'=>'/articles.php',      'type'=>'part', 'category'=>'news'), 
	);
	dump($walker->index('http://' . $domainName . '/', TRUE));
	echo '<h1>done!</h1>';
	echo $walker->stopWatch->toHtml();
}
*/

function indexBlueshoesOrg(&$wse, &$walker, &$profile) {
	$domainName = 'www.blueshoes.org';
	$profile->limitDomains = array($domainName, 'us.blueshoes.org'); //, 'developer.blueshoes.org'
	$profile->ignoreFileExtensions[] = 'doc'; //pdf and doc files are equal. makes no sense to use both.
	$profile->waitAfterIndex = 5;
	//$profile->ignoreUrls = array(
	//	array('value'=>'phoneBook.php',  'type'=>'file'), 
	//);
	//$profile->allowUrls = array(
	//	array('value'=>'showinterpret.asp',  'type'=>'file'), 
	//);
	dump($walker->index('http://' . $domainName . '/', TRUE));
	echo '<h1>done!</h1>';
	echo $walker->stopWatch->toHtml();
}

function indexPhpCompleteCom(&$wse, &$walker, &$profile) {
	$domainName = 'www.phpcomplete.com';
	$profile->limitDomains = array($domainName);
	
	$profile->waitAfterIndex = 2;
	$profile->ignoreUrls = array(
		//array('value'=>'/ads/',            'type'=>'part'), 
	);
	$profile->allowUrls = array(
		array('value'=>'/tutorials.php',     'type'=>'part', 'category'=>'tutorials'), 
		array('value'=>'/users.php',         'type'=>'part', 'category'=>'people'), 
		array('value'=>'/devblogs.php',      'type'=>'part', 'category'=>'news'), 
		array('value'=>'/articles.php',      'type'=>'part', 'category'=>'news'), 
	);
	$profile->categories = array(
		array('value'=>'/tutorials.php',     'type'=>'part', 'category'=>'tutorials'), 
		array('value'=>'/users.php',         'type'=>'part', 'category'=>'people'), 
		array('value'=>'/devblogs.php',      'type'=>'part', 'category'=>'news'), 
		array('value'=>'/articles.php',      'type'=>'part', 'category'=>'news'), 
	);
	
	dump($walker->index('http://' . $domainName . '/', TRUE));
	echo '<h1>done!</h1>';
	echo $walker->stopWatch->toHtml();
}

function indexPhpFreaksCom(&$wse, &$walker, &$profile) {
	$domainName = 'www.phpfreaks.com';
	$profile->limitDomains = array($domainName);
	
	$profile->waitAfterIndex = 2;
	$profile->ignoreUrls = array(
		array('value'=>'/ads/',            'type'=>'part'), 
		array('value'=>'/phpmanual/',      'type'=>'part'), 
		array('value'=>'/apache_manual/',  'type'=>'part'), 
		array('value'=>'/mysqlmanual/',    'type'=>'part'), 
		array('value'=>'/gtkmanual/',      'type'=>'part'), 
		array('value'=>'/pear_manual/',    'type'=>'part'), 
		array('value'=>'manual',           'type'=>'part'), //fuck it, too many manuals.
		array('value'=>'/phpref/',         'type'=>'part'), //good idea but empty (no content).
		array('value'=>'cmd=download',     'type'=>'part'), 
		array('value'=>'print.php',        'type'=>'part'), 
		array('value'=>'/tutorial_comment/', 'type'=>'part'), 
	);
	$profile->allowUrls = array(
		array('value'=>'/tutorials/',     'type'=>'part', 'category'=>'tutorials'), 
		array('value'=>'/ututorials.php', 'type'=>'part', 'category'=>'tutorials'), 
		array('value'=>'/articles/',      'type'=>'part', 'category'=>'news'), 
		array('value'=>'/script/',        'type'=>'part', 'category'=>'code'), 
		array('value'=>'/scripts/',       'type'=>'part', 'category'=>'code'), 
		array('value'=>'/quickcode/',     'type'=>'part', 'category'=>'snippet'), 
	);
	$profile->categories = array(
		array('value'=>'/tutorials/',     'type'=>'part', 'category'=>'tutorials'), 
		array('value'=>'/ututorials.php', 'type'=>'part', 'category'=>'tutorials'), 
		array('value'=>'/articles/',      'type'=>'part', 'category'=>'news'), 
		array('value'=>'/scripts/',       'type'=>'part', 'category'=>'code'), 
		array('value'=>'/quickcode/',     'type'=>'part', 'category'=>'snippet'), 
	);
	
	dump($walker->index('http://' . $domainName . '/', TRUE));
	echo '<h1>done!</h1>';
	echo $walker->stopWatch->toHtml();
}

function indexPhpWeblogsCom(&$wse, &$walker, &$profile) {
	$domainName = 'php.weblogs.com';
	$profile->limitDomains = array($domainName);
	
	$profile->waitAfterIndex = 2;
	$profile->ignoreUrls = array(
		array('value'=>'adodb',      'type'=>'part'), 
		array('value'=>'phplens',    'type'=>'part'), 
		array('value'=>'/member/',   'type'=>'part'), 
		array('value'=>'/discuss/',  'type'=>'part'), 
	);
	$profile->allowUrls = array(
		//array('value'=>'/news/',    'type'=>'part', 'category'=>'news'), 
	);
	
	dump($walker->index('http://' . $domainName . '/', TRUE));
	echo '<h1>done!</h1>';
	echo $walker->stopWatch->toHtml();
}

function indexPhpMagDe(&$wse, &$walker, &$profile) {
	$domainName = 'www.phpmag.de';
	$profile->limitDomains = array($domainName);
	
	$profile->waitAfterIndex = 2;
	$profile->ignoreUrls = array(
		array('value'=>'/itr/banner/',  'type'=>'part'), 
	);
	$profile->allowUrls = array(
		//array('value'=>'/news/',    'type'=>'part', 'category'=>'news'), 
	);
	
	dump($walker->index('http://' . $domainName . '/', TRUE));
	//dump($walker->_crawlUrlStack);
	echo '<h1>done!</h1>';
	echo $walker->stopWatch->toHtml();
}

function indexPhpBuilderCom(&$wse, &$walker, &$profile) {
	$domainName = 'www.phpbuilder.com';
	$profile->limitDomains = array($domainName);
	
	//$profile->ignoreFileExtensions[] = 'doc';
	$profile->waitAfterIndex = 2;
	$profile->ignoreUrls = array(
		//array('value'=>'phoneBook.php',  'type'=>'file'), 
		array('value'=>'/manual/',  'type'=>'part'), 
		array('value'=>'/mail/',    'type'=>'part'), 
		array('value'=>'/RealMedia/', 'type'=>'part'), 
		array('value'=>'/search/', 'type'=>'part'), 
		array('value'=>'/images/', 'type'=>'part'), 
		array('value'=>'/annotate/',  'type'=>'part'), 
		array('value'=>'rss_feed.php',  'type'=>'file'), 
		array('value'=>'/links/',  'type'=>'part'), 
		array('value'=>'/features/',  'type'=>'part'), //seems to be an old site structure that's still linked, but all 404.
	);
	$profile->allowUrls = array(
		array('value'=>'/news/',    'type'=>'part', 'category'=>'news'), 
		array('value'=>'/columns/', 'type'=>'part', 'category'=>'articles'), 
		//array('value'=>'/forum/',   'type'=>'part', 'category'=>'forum'), 
		//array('value'=>'/board/',   'type'=>'part', 'category'=>'forum'), 
		array('value'=>'/snippet/', 'type'=>'part', 'category'=>'code'), 
		array('value'=>'/tips/',    'type'=>'part', 'category'=>'tips'), 
		//array('value'=>'/mail/',    'type'=>'part', 'category'=>'mail'), 
		array('value'=>'/people/',  'type'=>'part', 'category'=>'people'), 
	);
	$profile->categories = array(
		array('value'=>'/news/',    'type'=>'part', 'category'=>'news'), 
		array('value'=>'/columns/', 'type'=>'part', 'category'=>'articles'), 
		array('value'=>'/forum/',   'type'=>'part', 'category'=>'forum'), 
		array('value'=>'/board/',   'type'=>'part', 'category'=>'forum'), 
		array('value'=>'/snippet/', 'type'=>'part', 'category'=>'code'), 
		array('value'=>'/tips/',    'type'=>'part', 'category'=>'tips'), 
		array('value'=>'/mail/',    'type'=>'part', 'category'=>'mail'), 
		array('value'=>'/people/',  'type'=>'part', 'category'=>'people'), 
	);
	
	$walker->_crawlUrlStack[] = 'http://www.phpbuilder.com/columns/index.php3?cat=all';
	$walker->_crawlUrlStack[] = 'http://www.phpbuilder.com/snippet/';
	$walker->_crawlUrlStack[] = 'http://www.phpbuilder.com/snippet/browse.php?by=cat&cat=16';
	
	dump($walker->index('http://' . $domainName . '/', TRUE));
	//dump($walker->_crawlUrlStack);
	echo '<h1>done!</h1>';
	echo $walker->stopWatch->toHtml();
}

?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">

<html>
<head>
	<title>PHP Search</title>
	<style>
		body {
			font-family: arial, verdana;
			font-size: 12px;
		}

.bsTabRegisterOn {
  height: 25;
  width: 100;
  background-color:#182842;
	color: white;
  cursor:pointer; cursor:hand;
  font-family: arial;
  font-size: 12px;
  text-align: center;
  font-weight: bold;
  border-top: 1px solid white;
  border-right: 1px solid #404040;
  border-left: 1px solid white;
}
.bsTabRegisterOff {
  height: 20;
  width: 80;
  background-color:#7B7984;
	color: white;
  cursor:pointer; cursor:hand;
  font-family: arial;
  font-size: 12px;
  text-align: center;
  border-top: 1px solid white;
  border-right: 1px solid #404040;
  border-left: 1px solid white;
}
td {
  font-family: arial;
  font-size: 12px;
}
</style>
<script type="text/javascript">
<!--
	function doSearch(tab) {
		//document.forms['search'].elements['c'].value = tab;
		//document.forms['search'].submit();
		document.frmSearch.c.value = tab;
		document.frmSearch.submit();
	}
// -->
</script>

</head>

<body bgcolor="white">

<table border="0" cellspacing="0" cellpadding="0" align="center">
	<tr>
		<?php
			$cats = array('All', 'Manual', 'Code', 'Articles', 'News', 'People');
			$currentCat = (isSet($_REQUEST['c'])) ? $_REQUEST['c'] : 'All';
			foreach($cats as $cat) {
				echo '<td valign="bottom">';
				//echo '<a href="' . $_SERVER['PHP_SELF'] . '?c=' . $cat . '&query=' . $_REQUEST['query'] . '">';
				echo '<div class="bsTabRegister' . (($cat === $currentCat) ? 'On' : 'Off') . '" onClick="doSearch(\'' . $cat . '\');">&nbsp;';
				echo $cat;
				echo '&nbsp;</div>';
				//echo '</a>';
				echo '</td>';
			}
		?>
	</tr>
</table>
<table width="100%" border="0" cellspacing="0" cellpadding="0" align="center">
	<tr>
		<td width="100%" height="80" colspan="100%" align="center" bgcolor="#DDE2E6">
			<form name="frmSearch" id="frmSearch" action="<?php echo $_SERVER['PHP_SELF'];?>" method="post">
			<?php
			bs_lazyLoadClass('html/Bs_HtmlUtil.class.php');
			?>
			Search for: <input type="text" name="query" value="<?php if (!empty($_REQUEST['query'])) echo $Bs_HtmlUtil->filterForHtml($_REQUEST['query']);?>"> <input type="submit" name="send" value="Search">
			<!--
			<br>
			Language: <input type="radio" name="language" value="*">any, <input type="radio" name="language" value="en">english, <input type="radio" name="language" value="de">german 
			-->
			<input type="hidden" name="c" value="<?php echo $currentCat;?>">
			</form>
		</td>
	</tr>
</table>
<br>

<?php
if (isSet($_REQUEST['c'])) {
	switch ($_REQUEST['c']) {
		case 'All':
			$categories = NULL;
			break;
		case 'Manual':
			$categories = array('manual'=>TRUE);
			break;
		case 'Code':
			$categories = array('applications'=>TRUE, 'code'=>TRUE);
			break;
		case 'Articles':
			$categories = array('articles'=>TRUE, 'tutorials'=>TRUE, 'tips'=>TRUE);
			break;
		case 'News':
			$categories = array('news'=>TRUE, 'advisories'=>TRUE);
			break;
		case 'People':
			$categories = array('people'=>TRUE, 'companies'=>TRUE);
			break;
	}
} else {
	$categories = NULL;
}

/*
| books        |

| forum        |
| mail         |
(newsgroups)

| jobs         |
*/

if (!empty($_REQUEST['query'])) {
	$searchFeatures = array(
		'part'      => FALSE, 
		'stemming'  => FALSE, 
		'metaphone' => FALSE, 
		'soundex'   => FALSE, 
		'synonyme'  => FALSE, 
		'caching'   => FALSE, 
		'hints'     => 'auto', 
	);
	/*
	$searchFeatures = array(
		'part'      => TRUE, 
		'stemming'  => TRUE, 
		'metaphone' => TRUE, 
		'soundex'   => TRUE, 
		'synonyme'  => TRUE, 
		'caching'   => TRUE, 
		'hints'     => 'auto', 
	);
	*/
	$searcher = &$wse->getSearcher($profileName);
	$searcher->urlMaxDisplayLength = 80;
	$searcher->searchStyleHead     = '__NUM_RESULTS_TOTAL__ pages found.<br><br>__HINTS_STRING__<br><ol start="__OFFSET++__">';
	if (isSet($_REQUEST['limit']) && isSet($_REQUEST['offset'])) {
		echo $searcher->search($_REQUEST['query'], (int)$_REQUEST['limit'], (int)$_REQUEST['offset'], $searchFeatures, $categories);
	} else {
		echo $searcher->search($_REQUEST['query'], 10, 0, $searchFeatures, $categories);
	}
	echo $searcher->getScrollbar();
	echo '<br><br>';
	echo $searcher->_isSearcher->stopWatch->toHtml();

}
?>


<br>
<center>(c) BlueShoes - Searching 25731 pages in 42 sites.</center>

</body>
</html>

