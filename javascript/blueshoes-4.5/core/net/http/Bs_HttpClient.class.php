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
define('BS_HTTPCLIENT_VERSION',      '4.5.$Revision: 1.4 $');if (!@$APP) require_once($_SERVER['DOCUMENT_ROOT'] . $GLOBALS['APP']['path']['diffFromDocRootToGlobalConf'] . 'global.conf.php');require_once($APP['path']['core'] . 'net/Bs_NetApplication.class.php');class Bs_HttpClient extends Bs_NetApplication {var $port = 80;var $sendProtocolVersion = 'HTTP/1.0';var $closeConnection = TRUE;var $receivedProtocolVersion = '';var $method = 'GET';var $userAgent = 'BlueShoes Walker 4.5'; var $acceptType = array('*/*', 'image/gif', 'image/x-xbitmap', 'image/jpeg');var $addHeaders = NULL;var $postData = NULL;var $_authenticationBasic;var $followRedirect = 5;var $numFollowed = 0;var $redirectHistory = NULL;var $acceptCookies = 3;var $receivedCookies = NULL;var $responseCode = NULL;var $parseHeader = FALSE;var $headerRaw = NULL;var $headerParsed = NULL;var $stopWatch;function Bs_HttpClient() {$this->Bs_NetApplication(); }
function fetchPage($path, $host=NULL, $port=NULL, $postData=NULL, $method=NULL, $tryReconnect=TRUE) {if ($this->stopWatch) $this->stopWatch->takeTime('fetchPage() for ' . $path . ' in ' . __FILE__ . ':' . __LINE__);$this->responseCode            = NULL;$this->headerParsed            = NULL;$this->headerRaw               = NULL;$this->numFollowed             = 0;$this->redirectHistory         = NULL;$this->receivedProtocolVersion = '';$this->receivedCookies         = NULL;if (strpos($path, '://')) {$t = @parse_url($path);if (!((is_array($t)) && (isSet($t['scheme'])) && (isSet($t['host'])))) 
return new Bs_Exception("not a valid url: '{$path}'", __FILE__, __LINE__);if ((isSet($t['path'])) && ($t['path'] != '')) {$path = $t['path'];} else {$path = '/';}
if (isSet($t['query']) && !empty($t['query'])) {$path .= '?' . $t['query'];}
$host = $t['host'];$port = ((isSet($t['port'])) && ($t['port'] > 0)) ? $t['port'] : 80;} else {if (is_null($host)) $host = $this->host;if (is_null($port)) $port = $this->port;if ((is_null($host)) || (is_null($port))) 
return new Bs_Exception("missing data in fetchPage(). host was: '{$host}', port was: '{$port}'.", __FILE__, __LINE__);}
$method   = (is_null($method))   ? $this->method   : $method;$postData = (is_null($postData)) ? $this->postData : $postData; if (((is_object($this->_Bs_SocketClient)) && ($this->_Bs_SocketClient->getState())) && ((is_null($host)) || ($this->host == $host)) && ((is_null($port)) || ($this->port == $port))) {$this->_useTempConnection = FALSE;$connObj = &$this->_Bs_SocketClient;} else {$this->_useTempConnection = TRUE;$status = $this->connect($host, $port);if (isEx($status)) {$status->stackTrace('was here in fetchPage()', __FILE__, __LINE__);return $status;}
$connObj = &$this->_Bs_SocketClient_Temp;}
if ($this->stopWatch) $this->stopWatch->takeTime('fetchPage() ' . __FILE__ . ':' . __LINE__);$success = FALSE;do { if (!$connObj->writeLine("{$method} {$path} {$this->sendProtocolVersion}")) break;if (!$connObj->writeLine("Host: {$host}")) break; if (!$connObj->writeLine("User-Agent: {$this->userAgent}")) break;if (is_array($this->acceptType)) {reset($this->acceptType);while(list($k) = each($this->acceptType)) {if (!$connObj->writeLine("Accept: {$this->acceptType[$k]}")) break 2;}
}
if ($this->stopWatch) $this->stopWatch->takeTime('fetchPage() ' . __FILE__ . ':' . __LINE__);if (is_array($this->addHeaders)) {reset($this->addHeaders);while(list($k) = each($this->addHeaders)) {if (!$connObj->writeLine("{$k}: {$this->addHeaders[$k]}")) break 2;}
}
if (isSet($this->_authenticationBasic)) {if (!$connObj->writeLine('Authorization: Basic ' . base64_encode($this->_authenticationBasic['user'] . ':' . $this->_authenticationBasic['pass']))) break;}
if (($this->sendProtocolVersion !== 'HTTP/1.0') && $this->closeConnection) {if (!$connObj->writeLine('Connection: close')) break;}
if ($this->stopWatch) $this->stopWatch->takeTime('fetchPage() ' . __FILE__ . ':' . __LINE__);if ($method == 'POST') {if (is_array($postData)) 
$postData = $this->Bs_Url->hashArrayToQueryString($postData, '', '');if ((!is_string($postData)) || ($postData == '')) $postData = 'dev0_564654654=dev0'; if (!$connObj->writeLine("Content-type: application/x-www-form-urlencoded")) break;if (!$connObj->writeLine('Content-length: ' . strlen($postData))) break;if (!$connObj->writeLine('')) break; if (!$connObj->write($postData)) break;}
if (!$connObj->writeLine('')) break; $success = TRUE;} while (FALSE);if ($this->stopWatch) $this->stopWatch->takeTime('fetchPage() ' . __FILE__ . ':' . __LINE__);if (!$success) return new Bs_Exception('error while sending the request...', __FILE__, __LINE__);$connObj->setState(BS_SOCKETCLIENT_REQUEST_SENT);$success = TRUE;$header  = array();$content = '';if ($this->stopWatch) $this->stopWatch->takeTime('fetchPage() ' . __FILE__ . ':' . __LINE__);do {$line = $connObj->readLine();if (is_null($line)) {$success = FALSE;break;} elseif (isEx($line)) {$line->stackTrace('was here in fetchPage()', __FILE__, __LINE__);return $line;} elseif ($line == '') break;$header[] = $line;} while (TRUE);$connObj->setState(BS_SOCKETCLIENT_GOT_REPLY_HEADER);if ($this->stopWatch) $this->stopWatch->takeTime('fetchPage() ' . __FILE__ . ':' . __LINE__);if ($success) {do {$line = $connObj->readLine(FALSE);if (is_null($line)) {break;} elseif (isEx($line)) {$line->stackTrace('was here in fetchPage()', __FILE__, __LINE__);return $line;}
$content .= $line;} while (TRUE);$connObj->setState(BS_SOCKETCLIENT_GOT_REPLY_CONTENT);} else {if (($tryReconnect) && ($connObj->reconnect())) {return $this->fetchPage($path, $host, $port, $postData, $method, FALSE);} else {return new Bs_Exception('no or not full response header received', __FILE__, __LINE__);}
}
if (ereg('^(HTTP/[0-9]+\.[0-9]+) ([0-9]{3})', $header[0], $regs)) {$this->responseCode            = (int)$regs[2];$this->receivedProtocolVersion = $regs[1];}
if ($this->stopWatch) $this->stopWatch->takeTime('fetchPage() ' . __FILE__ . ':' . __LINE__);$this->headerRaw = &$header;if ($this->parseHeader) {$this->_parseHeader();}
if ($this->acceptCookies) {}
$t = (string)$this->responseCode;if (($t[0] == '3') && ($this->followRedirect > $this->numFollowed) && (($method === 'GET') || ($method === 'HEAD'))) {switch ($this->responseCode) {case 300: case 301: case 302: if ($this->stopWatch) $this->stopWatch->takeTime('fetchPage() in switch ' . __FILE__ . ':' . __LINE__);$location = $this->getHeaderValue('Location');if ((!is_null($location)) && ($this->Bs_Url->checkSyntax($location))) {$this->numFollowed++;$this->redirectHistory[] = $host . ':' . $port . $path;return $this->fetchPage($location); }
}
}
if ($this->stopWatch) $this->stopWatch->takeTime('fetchPage() ' . __FILE__ . ':' . __LINE__);if ($this->_useTempConnection) $connObj->disconnect();return $content;}
function getHeaderValue($key) {$key = strToLower($key);if (!is_array($this->headerParsed)) $this->_parseHeader();if ((is_array($this->headerParsed)) && (isSet($this->headerParsed[$key]))) return $this->headerParsed[$key];return NULL;}
function setAuthenticationBasic($user='', $pass='') {if ((empty($user)) || (empty($pass))) {unset($this->_authenticationBasic);} else {$this->_authenticationBasic = array('user'=>$user, 'pass'=>$pass);}
}
function responseCodeInfo($code=NULL, $protocol='HTTP/1.1') {if (is_null($code)) {$code     = $this->responseCode;$protocol = $this->receivedProtocolVersion;}
$ret = array($code);switch ($code) {case 100:
$ret[] = 'Continue';$ret[] = 'The client SHOULD continue with its request. This interim response is used to inform the client that the initial part of the request has been received and has not yet been rejected by the server. The client SHOULD continue by sending the remainder of the request or, if the request has already been completed, ignore this response. The server MUST send a final response after the request has been completed. See section 8.2.3 for detailed discussion of the use and handling of this status code.';if ($protocol == 'HTTP/1.0')  $ret[2] = "Undefined for HTTP/1.0 but for HTTP/1.1 it would be: \n\n" . $ret[2];break;case 101:
$ret[] = 'Switching Protocols';$ret[] = "The server understands and is willing to comply with the client's request, via the Upgrade message header field (section 14.42), for a change in the application protocol being used on this connection. The server will switch protocols to those defined by the response's Upgrade header field immediately after the empty line which terminates the 101 response.\n\nThe protocol SHOULD be switched only when it is advantageous to do so. For example, switching to a newer version of HTTP is advantageous over older versions, and switching to a real-time, synchronous protocol might be advantageous when delivering resources that use such features.";if ($protocol == 'HTTP/1.0')  $ret[2] = "Undefined for HTTP/1.0 but for HTTP/1.1 it would be: \n\n" . $ret[2];break;case 200:
$ret[] = 'OK';$ret[] = "The request has succeeded. The information returned with the response is dependent on the method used in the request, for example:\n\nGET: an entity corresponding to the requested resource is sent in the response;\n\nHEAD: the entity-header fields corresponding to the requested resource are sent in the response without any message-body;\n\nPOST: an entity describing or containing the result of the action;";break;case 201:
$ret[] = 'Created';$ret[] = "The request has been fulfilled and resulted in a new resource being created. The newly created resource can be referenced by the URI(s) returned in the entity of the response, with the most specific URI for the resource given by a Location header field. The response SHOULD include an entity containing a list of resource characteristics and location(s) from which the user or user agent can choose the one most appropriate. The entity format is specified by the media type given in the Content-Type header field. The origin server MUST create the resource before returning the 201 status code. If the action cannot be carried out immediately, the server SHOULD respond with 202 (Accepted) response instead.\n\nA 201 response MAY contain an ETag response header field indicating the current value of the entity tag for the requested variant just created, see section 14.19.";break;case 202:
$ret[] = 'Accepted';$ret[] = "The request has succeeded. The information returned with the response is dependent on the method used in the request, for example:\n\nGET: an entity corresponding to the requested resource is sent in the response;\n\nHEAD: the entity-header fields corresponding to the requested resource are sent in the response without any message-body;\n\nPOST: an entity describing or containing the result of the action;";break;case 203:
$ret[] = 'Non-Authoritative Information';$ret[] = "The returned metainformation in the entity-header is not the definitive set as available from the origin server, but is gathered from a local or a third-party copy. The set presented MAY be a subset or superset of the original version. For example, including local annotation information about the resource might result in a superset of the metainformation known by the origin server. Use of this response code is not required and is only appropriate when the response would otherwise be 200 (OK).";if ($protocol == 'HTTP/1.0')  $ret[2] = "Undefined for HTTP/1.0 but for HTTP/1.1 it would be: \n\n" . $ret[2];break;case 204:
$ret[] = 'No Content';$ret[] = "The server has fulfilled the request but does not need to return an entity-body, and might want to return updated metainformation. The response MAY include new or updated metainformation in the form of entity-headers, which if present SHOULD be associated with the requested variant.\n\nIf the client is a user agent, it SHOULD NOT change its document view from that which caused the request to be sent. This response is primarily intended to allow input for actions to take place without causing a change to the user agent's active document view, although any new or updated metainformation SHOULD be applied to the document currently in the user agent's active view.\n\nThe 204 response MUST NOT include a message-body, and thus is always terminated by the first empty line after the header fields.";break;case 205:
$ret[] = 'Reset Content';$ret[] = "The server has fulfilled the request and the user agent SHOULD reset the document view which caused the request to be sent. This response is primarily intended to allow input for actions to take place via user input, followed by a clearing of the form in which the input is given so that the user can easily initiate another input action. The response MUST NOT include an entity.";if ($protocol == 'HTTP/1.0')  $ret[2] = "Undefined for HTTP/1.0 but for HTTP/1.1 it would be: \n\n" . $ret[2];break;case 206:
$ret[] = 'Partial Content';$ret[] = "The server has fulfilled the partial GET request for the resource. The request MUST have included a Range header field (section 14.35) indicating the desired range, and MAY have included an If-Range header field (section 14.27) to make the request conditional.\n\nThe response MUST include the following header fields:\n\n- Either a Content-Range header field (section 14.16) indicating the range included with this response, or a multipart/byteranges Content-Type including Content-Range fields for each part. If a Content-Length header field is present in the response, its value MUST match the actual number of OCTETs transmitted in the message-body.\n\n- Date\n\n- ETag and/or Content-Location, if the header would have been sent in a 200 response to the same request\n\n- Expires, Cache-Control, and/or Vary, if the field-value might differ from that sent in any previous response for the same variant\n\nIf the 206 response is the result of an If-Range request that used a strong cache validator (see section 13.3.3), the response SHOULD NOT include other entity-headers. If the response is the result of an If-Range request that used a weak validator, the response MUST NOT include other entity-headers; this prevents inconsistencies between cached entity-bodies and updated headers. Otherwise, the response MUST include all of the entity-headers that would have been returned with a 200 (OK) response to the same request.\n\nA cache MUST NOT combine a 206 response with other previously cached content if the ETag or Last-Modified headers do not match exactly, see 13.5.4.\n\nA cache that does not support the Range and Content-Range headers MUST NOT cache 206 (Partial) responses.";if ($protocol == 'HTTP/1.0')  $ret[2] = "Undefined for HTTP/1.0 but for HTTP/1.1 it would be: \n\n" . $ret[2];break;case 300:
$ret[] = 'Multiple Choices';$ret[] = "--description not coded yet--";break;case 301:
$ret[] = 'Moved Permanently';$ret[] = "--description not coded yet--";break;case 302:
if ($protocol == 'HTTP/1.0') {$ret[] = 'Moved Temporarily';$ret[] = "--description not coded yet--";} else {$ret[] = 'Found';$ret[] = "--description not coded yet--";}
break;case 303:
$ret[] = 'See Other';$ret[] = "--description not coded yet--";break;case 304:
$ret[] = 'Not Modified';$ret[] = "--description not coded yet--";break;case 305:
$ret[] = 'Use Proxy';$ret[] = "--description not coded yet--";break;case 306:
$ret[] = '(Unused)';$ret[] = "The 306 status code was used in a previous version of the specification, is no longer used, and the code is reserved.";if ($protocol == 'HTTP/1.0')  $ret[2] = "Undefined for HTTP/1.0 but for HTTP/1.1 it would be: \n\n" . $ret[2];break;case 307:
$ret[] = 'Temporary Redirect';$ret[] = "--description not coded yet--";if ($protocol == 'HTTP/1.0')  $ret[2] = "Undefined for HTTP/1.0 but for HTTP/1.1 it would be: \n\n" . $ret[2];break;case 400:
$ret[] = 'Bad Request';$ret[] = "--description not coded yet--";break;case 401:
$ret[] = 'Unauthorized';$ret[] = "--description not coded yet--";break;case 402:
$ret[] = 'Payment Required';$ret[] = "--description not coded yet--";if ($protocol == 'HTTP/1.0')  $ret[2] = "Undefined for HTTP/1.0 but for HTTP/1.1 it would be: \n\n" . $ret[2];break;case 403:
$ret[] = 'Forbidden';$ret[] = "--description not coded yet--";break;case 404:
$ret[] = 'Not Found';$ret[] = "--description not coded yet--";break;case 405:
$ret[] = 'Method Not Allowed';$ret[] = "--description not coded yet--";if ($protocol == 'HTTP/1.0')  $ret[2] = "Undefined for HTTP/1.0 but for HTTP/1.1 it would be: \n\n" . $ret[2];break;case 406:
$ret[] = 'Not Acceptable';$ret[] = "--description not coded yet--";if ($protocol == 'HTTP/1.0')  $ret[2] = "Undefined for HTTP/1.0 but for HTTP/1.1 it would be: \n\n" . $ret[2];break;case 407:
$ret[] = 'Proxy Authentication Required';$ret[] = "--description not coded yet--";if ($protocol == 'HTTP/1.0')  $ret[2] = "Undefined for HTTP/1.0 but for HTTP/1.1 it would be: \n\n" . $ret[2];break;case 408:
$ret[] = 'Request Timeout';$ret[] = "--description not coded yet--";if ($protocol == 'HTTP/1.0')  $ret[2] = "Undefined for HTTP/1.0 but for HTTP/1.1 it would be: \n\n" . $ret[2];break;case 409:
$ret[] = 'Conflict';$ret[] = "--description not coded yet--";if ($protocol == 'HTTP/1.0')  $ret[2] = "Undefined for HTTP/1.0 but for HTTP/1.1 it would be: \n\n" . $ret[2];break;case 410:
$ret[] = 'Gone';$ret[] = "--description not coded yet--";if ($protocol == 'HTTP/1.0')  $ret[2] = "Undefined for HTTP/1.0 but for HTTP/1.1 it would be: \n\n" . $ret[2];break;case 411:
$ret[] = 'Length Required';$ret[] = "--description not coded yet--";if ($protocol == 'HTTP/1.0')  $ret[2] = "Undefined for HTTP/1.0 but for HTTP/1.1 it would be: \n\n" . $ret[2];break;case 412:
$ret[] = 'Precondition Failed';$ret[] = "--description not coded yet--";if ($protocol == 'HTTP/1.0')  $ret[2] = "Undefined for HTTP/1.0 but for HTTP/1.1 it would be: \n\n" . $ret[2];break;case 413:
$ret[] = 'Precondition Failed';$ret[] = "--description not coded yet--";if ($protocol == 'HTTP/1.0')  $ret[2] = "Undefined for HTTP/1.0 but for HTTP/1.1 it would be: \n\n" . $ret[2];break;case 413:
$ret[] = 'Request Entity Too Large';$ret[] = "--description not coded yet--";if ($protocol == 'HTTP/1.0')  $ret[2] = "Undefined for HTTP/1.0 but for HTTP/1.1 it would be: \n\n" . $ret[2];break;case 414:
$ret[] = 'Request-URI Too Long';$ret[] = "--description not coded yet--";if ($protocol == 'HTTP/1.0')  $ret[2] = "Undefined for HTTP/1.0 but for HTTP/1.1 it would be: \n\n" . $ret[2];break;case 415:
$ret[] = 'Unsupported Media Type';$ret[] = "--description not coded yet--";if ($protocol == 'HTTP/1.0')  $ret[2] = "Undefined for HTTP/1.0 but for HTTP/1.1 it would be: \n\n" . $ret[2];break;case 416:
$ret[] = 'Requested Range Not Satisfiable';$ret[] = "--description not coded yet--";if ($protocol == 'HTTP/1.0')  $ret[2] = "Undefined for HTTP/1.0 but for HTTP/1.1 it would be: \n\n" . $ret[2];break;case 417:
$ret[] = 'Expectation Failed';$ret[] = "--description not coded yet--";if ($protocol == 'HTTP/1.0')  $ret[2] = "Undefined for HTTP/1.0 but for HTTP/1.1 it would be: \n\n" . $ret[2];break;case 500:
$ret[] = 'Internal Server Error';$ret[] = "--description not coded yet--";break;case 501:
$ret[] = 'Not Implemented';$ret[] = "--description not coded yet--";break;case 502:
$ret[] = 'Bad Gateway';$ret[] = "--description not coded yet--";break;case 503:
$ret[] = 'Service Unavailable';$ret[] = "--description not coded yet--";break;case 504:
$ret[] = 'Gateway Timeout';$ret[] = "--description not coded yet--";if ($protocol == 'HTTP/1.0')  $ret[2] = "Undefined for HTTP/1.0 but for HTTP/1.1 it would be: \n\n" . $ret[2];break;case 505:
$ret[] = 'HTTP Version Not Supported';$ret[] = "--description not coded yet--";if ($protocol == 'HTTP/1.0')  $ret[2] = "Undefined for HTTP/1.0 but for HTTP/1.1 it would be: \n\n" . $ret[2];break;}
return $ret;}
function _parseHeader() {$this->headerParsed = array();reset($this->headerRaw);while(list($k) = each($this->headerRaw)) {if (strpos($this->headerRaw[$k], ': ') > 1) {  list($hKey, $hVal) = explode(': ', $this->headerRaw[$k], 2);$hKey = strToLower(trim($hKey));$hVal = trim($hVal);if (isSet($this->headerParsed[$hKey])) {if (is_string($this->headerParsed[$hKey])) {$this->headerParsed[$hKey] = array($this->headerParsed[$hKey], $hVal);} else {$this->headerParsed[$hKey][] = $hVal;}
} else {$this->headerParsed[$hKey] = $hVal;}
}
}
}
function _readCookie() {}
function getUserAgent($os='win', $client='ie', $ver='5.5', $lang='en') {switch ($os) {case 'win':
switch ($client) {case 'ie':
return '';break;case 'ns':
case '':
default:
}
break;}
}
function randomUserAgent() {if (!isSet($ua)) {static $ua = array();$ua['ie'] = array('', 
'', 
'');$ua['ns'] = array('', 
'', 
'');}
srand((double)microtime() * 10000000);return array_rand($ua);}
}
?>