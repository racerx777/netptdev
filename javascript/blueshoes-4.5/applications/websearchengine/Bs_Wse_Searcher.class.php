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
*/?>
<?php
define('BS_WSE_SEARCHER_VERSION',      '4.5.$Revision: 1.5 $');

if (!isSet($GLOBALS['APP']['path']['diffFromDocRootToGlobalConf'])) $GLOBALS['APP']['path']['diffFromDocRootToGlobalConf'] = '../';
require_once($_SERVER['DOCUMENT_ROOT'] . $GLOBALS['APP']['path']['diffFromDocRootToGlobalConf'] . 'global.conf.php');
require_once($APP['path']['core'] . 'net/Bs_Url.class.php');
require_once($APP['path']['core'] . 'util/Bs_StopWatch.class.php');
require_once($APP['path']['core'] . 'lang/Bs_Logger.class.php');

require_once($APP['path']['plugins'] . 'indexserver/Bs_Is_IndexServer.class.php');


function highlightStringComplicated_sort($a, $b) {
    $aa = strlen($a);
    $bb = strlen($b);
    if ($aa == $bb) return 0;
    return ($aa > $bb) ? -1 : 1;
}


class Bs_Wse_Searcher extends Bs_Object {
  
    
    var $Bs_Wse_WebSearchEngine;
    
    
    var $Bs_Url;
    
    var $_bsDb;
    
    var $_isSearcher;
    
    
  var $_profile;
    
    var $stopWatch;
    
    
  var $searchStyleHead = '__HINTS_STRING__<br/><br/>__NUM_RESULTS_TOTAL__ Seiten gefunden.<br/><br/><ol start="__OFFSET++__">';
  var $searchStyleBody = '<li>__LINK_TITLE__<br/>__DESCRIPTION__<br/>__LINK_URL__<hr size="1" noshade="true"/></li>';
  var $searchStyleFoot = '</ol>';
  
    var $urlMaxDisplayLength = 100;
    
    
    var $numResults;
    
    var $_limit;
    
    var $_offset;
    
    var $_searchString;
    
    
    var $_highlightStringWordCache;
    
    
    function Bs_Wse_Searcher(&$Bs_Wse_WebSearchEngine, &$profile, &$bsDb) {
    parent::Bs_Object();  $this->Bs_Wse_WebSearchEngine = &$Bs_Wse_WebSearchEngine;
    $this->_profile               = &$profile;
    $this->_bsDb                  = &$bsDb;
        
        $this->Bs_Url = &$GLOBALS['Bs_Url'];
        
        $this->Bs_Wse_WebSearchEngine->Bs_Is_IndexServer->setProfile($profile->_isProfile); $this->_isSearcher = &$this->Bs_Wse_WebSearchEngine->Bs_Is_IndexServer->getSearcher($profile->profileName);
        if (isEx($this->_isSearcher)) {
            $this->_isSearcher->stackDump('die');
        } elseif ($this->_isSearcher === FALSE) {
            die('could not create searcher in ' . __FILE__ . ' on line ' . __LINE__);
        }
        
    }
    
    
    function setDbByObj(&$bsDb) {
        unset($this->_bsDb);
        $this->_bsDb = &$bsDb;
        $this->_createDbTables();
    }
    
    function setDbByDsn($dsn) {
        bs_lazyLoadClass('db/Bs_Db.class.php');
        $bsDb = &getDbObject($dsn);
        if (isEx($bsDb)) {
          $bsDb->stackTrace('was here in setDbByDsn()', __FILE__, __LINE__, 'fatal');
            return $bsDb;
        }
        $this->_bsDb = &$bsDb;
        $this->_createDbTables();
        return TRUE;
    }
    
    
    function search($searchString, $limit=10, $offset=0, $features=NULL, $categories=NULL) {
        $results = $this->_isSearcher->search($searchString, NULL, $features);
        
        $this->numResults    = sizeOf($results);
        $this->_limit        = $limit;
        $this->_offset       = $offset;
        $this->_searchString = $searchString;
        
        $searchStyleHead = $this->searchStyleHead;
        $searchStyleHead = str_replace('__TIME_TAKEN__',        $this->_isSearcher->searchTime, $searchStyleHead);
        $searchStyleHead = str_replace('__NUM_RESULTS_TOTAL__', $this->numResults,              $searchStyleHead);
        $searchStyleHead = str_replace('__HINTS_STRING__',      $this->_isSearcher->hintString, $searchStyleHead);
        $searchStyleHead = str_replace('__OFFSET__',            $offset,                        $searchStyleHead);
        $searchStyleHead = str_replace('__OFFSET++__',          $offset +1,                     $searchStyleHead);
        
    $ret = $searchStyleHead;
        
        $wordList    = array();
        $queryData = $this->_isSearcher->queryData;
        reset($queryData);
        while (list($k) = each($queryData)) {
            if ($queryData[$k]['operator'] === '!') continue;
            reset($queryData[$k]['tokens']);
            while (list($k2) = each($queryData[$k]['tokens'])) {
                if ($queryData[$k]['tokens'][$k2]['ignored']) continue;
                if (isSet($queryData[$k]['tokens'][$k2]['match']) && is_array($queryData[$k]['tokens'][$k2]['match'])) {
                    reset($queryData[$k]['tokens'][$k2]['match']);
                    while (list($k3) = each($queryData[$k]['tokens'][$k2]['match'])) {
                        $t = $queryData[$k]['tokens'][$k2]['match'][$k3];
                        
                        $wordList[] = $t['wordInfo']['caption'];
                    }
                }
            }
        }
        $i = 0;
        foreach($results as $url => $points) {
            $i++;
            if ($i < ($offset +1)) continue;
            
            $walker  = &$this->Bs_Wse_WebSearchEngine->getWalker($this->_profile->profileName);
            $record = $walker->fetchPageInfoByUrl($url);
            if (is_array($categories)) {
                if (!isSet($categories[$record['category']])) {
                    $i--;
                    continue;
                }
            }
            
            if (is_array($record)) { $urlAsText = $record['url'];
                if (strlen($urlAsText) > $this->urlMaxDisplayLength) $urlAsText = '...' . substr($urlAsText, -$this->urlMaxDisplayLength); $title = (!empty($record['title'])) ? $record['title'] : basename($urlAsText);
                if ($title === 'þÿ') $title = basename($urlAsText); $title = $this->highlightStringComplicated($title, $wordList, $record['url']);
                $urlAsText = $this->highlightStringComplicated($urlAsText, $wordList, $record['url']);
                
                if (substr($record['url'], -4) !== '.pdf') {
                    $description = $this->highlightStringComplicated($record['description'], $wordList, $record['url']);
                } else {
                    $description = $this->highlightStringComplicated($record['contentSnapshot'], $wordList, $record['url']);
                }
                
                $searchStyleBody = $this->searchStyleBody;
                $searchStyleBody = str_replace('__LINK_TITLE__',  '<a href="__URL__" title="__URL__">__TITLE__</a>', $searchStyleBody);
                $searchStyleBody = str_replace('__LINK_URL__',    '<a href="__URL__" title="__URL__">__URL_AS_TEXT__</a>',   $searchStyleBody);
                $searchStyleBody = str_replace('__DESCRIPTION__', $description,   $searchStyleBody);
                $searchStyleBody = str_replace('__TITLE__',       $title,         $searchStyleBody);
                $searchStyleBody = str_replace('__URL_AS_TEXT__', $urlAsText,     $searchStyleBody);
                $searchStyleBody = str_replace('__URL__',         $record['url'], $searchStyleBody);
                $ret .= $searchStyleBody;
            }
            if ($i >= ($offset + $limit)) break;
        }
        $ret .= $this->searchStyleFoot;
        
        $status = $this->_log($searchString, $this->numResults, $offset);
        
        return $ret;
    }
    
    function _getWordList($parsedQuery) {
        $wordList = array();
        foreach ($parsedQuery as $queryDataArr) {
            if ($queryDataArr['operator'] === '!') continue;
            if (isSet($queryDataArr['words'])) {
                foreach ($queryDataArr['words'] as $queryDataWords) {
                    $wordList[] = $queryDataWords['word'];
                }
            }
            if (isSet($queryDataArr['list'])) {
                $wordList = array_merge($wordList, $this->_getWordList($queryDataArr['list']));
            }
        }
        return $wordList;
    }
    
    
    function search2($searchString, $limit=10, $offset=0, $features=NULL, $categories=NULL) {
        $results = $this->_isSearcher->search2($searchString, NULL, $features);
        $this->numResults    = sizeOf($results);
        $this->_limit        = $limit;
        $this->_offset       = $offset;
        $this->_searchString = $searchString;
        
        $searchStyleHead = $this->searchStyleHead;
        $searchStyleHead = str_replace('__TIME_TAKEN__',        $this->_isSearcher->searchTime, $searchStyleHead);
        $searchStyleHead = str_replace('__NUM_RESULTS_TOTAL__', $this->numResults,              $searchStyleHead);
        $searchStyleHead = str_replace('__HINTS_STRING__',      $this->_isSearcher->hintString, $searchStyleHead);
        $searchStyleHead = str_replace('__OFFSET__',            $offset,                        $searchStyleHead);
        $searchStyleHead = str_replace('__OFFSET++__',          $offset +1,                     $searchStyleHead);
        
    $ret = $searchStyleHead;
        
        $wordList    = $this->_getWordList($this->_isSearcher->parsedQuery);
        $i = 0;
        foreach($results as $url => $points) {
            $i++;
            if ($i < ($offset +1)) continue;
            
            $walker  = &$this->Bs_Wse_WebSearchEngine->getWalker($this->_profile->profileName);
            $record = $walker->fetchPageInfoByUrl($url);
            if (is_array($categories)) {
                if (!isSet($categories[$record['category']])) {
                    $i--;
                    continue;
                }
            }
            
            if (is_array($record)) { $urlAsText = $record['url'];
                if (strlen($urlAsText) > $this->urlMaxDisplayLength) $urlAsText = '...' . substr($urlAsText, -$this->urlMaxDisplayLength); $title = (!empty($record['title'])) ? $record['title'] : basename($urlAsText);
                if ($title === 'þÿ') $title = basename($urlAsText); $title = $this->highlightStringComplicated($title, $wordList, $record['url']);
                $urlAsText = $this->highlightStringComplicated($urlAsText, $wordList, $record['url']);
                
                if (substr($record['url'], -4) !== '.pdf') {
                    $description = $this->highlightStringComplicated($record['description'], $wordList, $record['url']);
                } else {
                    $description = $this->highlightStringComplicated($record['contentSnapshot'], $wordList, $record['url']);
                }
                
                $searchStyleBody = $this->searchStyleBody;
                $searchStyleBody = str_replace('__LINK_TITLE__',  '<a href="__URL__" title="__URL__">__TITLE__</a>', $searchStyleBody);
                $searchStyleBody = str_replace('__LINK_URL__',    '<a href="__URL__" title="__URL__">__URL_AS_TEXT__</a>',   $searchStyleBody);
                $searchStyleBody = str_replace('__DESCRIPTION__', $description,   $searchStyleBody);
                $searchStyleBody = str_replace('__TITLE__',       $title,         $searchStyleBody);
                $searchStyleBody = str_replace('__URL_AS_TEXT__', $urlAsText,     $searchStyleBody);
                $searchStyleBody = str_replace('__URL__',         $record['url'], $searchStyleBody);
                $ret .= $searchStyleBody;
            }
            if ($i >= ($offset + $limit)) break;
        }
        $ret .= $this->searchStyleFoot;
        
        $status = $this->_log($searchString, $this->numResults, $offset);
        
        return $ret;
    }
    
    
    function getScrollbar() {
        if (($this->numResults <= ($this->_limit + $this->_offset)) && ($this->_offset == 0)) return ''; $ret     = array();
        $baseUrl = $_SERVER['PHP_SELF'] . '?query=' . urlencode($this->_searchString);
        $i       = 0;
        
        if ($this->_offset > 0) {
            do {
                $offset = $i * $this->_limit;
                if ($offset >= $this->_offset) break;
                $ret[] = '<a href="' . $baseUrl . '&limit=' . $this->_limit . '&offset=' . $offset . '">'  . ($i +1) . '</a>';
                $i++;
            } while (TRUE);
        }
        
        $ret[] = '<b>' . ($i +1) . '</b>'; $i++;
        
        if ($this->numResults > ($this->_limit + $this->_offset)) {
            do {
                $offset = $i * $this->_limit;
                if ($offset >= $this->numResults) break;
                $ret[] = '<a href="' . $baseUrl . '&limit=' . $this->_limit . '&offset=' . $offset . '">'  . ($i +1) . '</a>';
                $i++;
            } while (TRUE);
        }
        return join(' - ', $ret);
    }
    
    
    function _log($searchString, $recsFound, $offset) {
        $sql  = "INSERT INTO Bs_Wse_{$this->_profile->profileName}_SearchLog (searchString, numResults, offset, ip, host, userAgent) ";
        $sql .= "VALUES ('" . $this->_bsDb->escapeString($searchString) . "', {$recsFound}, {$offset}, ";
        $sql .= "'" . $this->_bsDb->escapeString($_SERVER['REMOTE_ADDR']) . "', ";
        $host = (isSet($_SERVER['REMOTE_HOST'])) ? $this->_bsDb->escapeString($_SERVER['REMOTE_HOST']) : '';
        $sql .= "'" . $host . "', ";
        $sql .= "'" . $this->_bsDb->escapeString($_SERVER['HTTP_USER_AGENT']) . "'";
        $sql .= ")";
        $status = $this->_bsDb->write($sql);
    if (isEx($status)) {
            $this->_profile->_createDbTables();
        $status = $this->_bsDb->write($sql);
        }
    if (isEx($status)) {
      $status->stackTrace('was here in _log()', __FILE__, __LINE__);
      return $status;
    }
        return TRUE;
    }
    
  function highlightStringComplicated($string, $wordList, $url) {
        $description = '';
        $origStrLen  = strlen($string);
        
        usort($wordList, 'highlightStringComplicated_sort');
        $replaceArray = array(
            array('ae', array('ä', 'ae', 'æ')), 
            array('oe', array('ö', 'oe', 'œ')), 
            array('ue', array('ü', 'ue')), 
            array('ss', array('ss', 'ß')), 
            array('aa', array('aa', 'å')), 
            array('a', array('a', 'à', 'á', 'â', 'ã', 'ä', 'å')), array('c', array('c', 'ç', '¢', '©')), 
            array('d', array('d', 'ð')), 
            array('e', array('e', 'è', 'é', 'ê', 'ë')), 
            array('f', array('f', 'ƒ')), 
            array('i', array('i', 'ì', 'í', 'î', 'ï', '¡')), 
            array('n', array('n', 'ñ')), 
            array('o', array('o', 'ò', 'ó', 'ô', 'õ', 'ö', 'ø')), 
            array('p', array('p', 'þ')), 
            array('r', array('r', '®')), 
            array('s', array('s', 'š')), 
            array('u', array('u', 'ù', 'ú', 'û', 'ü', 'µ')), 
            array('x', array('x', '÷')), 
            array('y', array('y', 'ý', 'ÿ', '¥')), 
            array('z', array('z', 'ž')), 
            array('', array()), 
            );
        
        if (strlen($string) > 255) $string = substr($string, 0, 252) . '...';
        foreach($wordList as $word) {
            if (isSet($this->_highlightStringWordCache[$word])) {
                $word = $this->_highlightStringWordCache[$word];
            } else {
                $wordUnmodified = $word;
                $i = 0;
                foreach($replaceArray as $replArr) {
                    $word = str_replace($replArr[0], '__' . $i . '__', $word);
                    $i++;
                }
                $i = 0;
                foreach($replaceArray as $replArr) {
                    $regExPart = '(?:' . join('|', $replArr[1]) . ')';
                    $word = str_replace('__' . $i . '__', $regExPart, $word);
                    $i++;
                }
                $this->_highlightStringWordCache[$wordUnmodified] = $word;
            }
            
            $pattern = '/' . $word . '/i';
            $string = preg_replace($pattern, '<b>\\0</b>', $string);
        }
        return $string;
        
        if (empty($description)) {
            $description = substr($string, 0, 255);
        }
        return $description;
    }
    
    function _logMessage($msg) {
        bs_logIt($msg, '', __LINE__, '', __FILE__);  echo $msg . "<br/>\n";
    }
    
    
    
}



if (basename($_SERVER['PHP_SELF']) == 'Bs_Wse_Searcher.class.php') {
}


?>