<?php 
  /**********************************************************************
  *  A very simple chatbox sample.
  *   It demonstrates the use of PHP "Objects-Method Propagation" in use
  *   JS-RS. (Simple Function Propagation is just as simple)
  */
  // Include the JsrsServer
  require_once($_SERVER['DOCUMENT_ROOT'] . '../global.conf.php');
  require_once($APP['path']['plugins'] . 'jsrs/JsrsServer.class.php');
  
  /***********************************************************
  * Save chat data so disk and offer to methods to the client:
  *   read()  : Read out the chat text collected so far.
  *   write() : Add text to the chat text.
	* 
  * @package    plugins_jsrs
	* @subpackage examples
  */
  class ChatboxServer {
    var $chatFile = 'chatBuffer.txt';
    
    // Internal methods
    function _tail($lineCount=10) {
      $ret = '';
      if (file_exists($this->chatFile)) {
        $t = file($this->chatFile);
        $t = array_slice($t, -$lineCount);
        $ret = join ('', $t);
      }
      return $ret;
    }
    
    // This is a methods to be propagated (See below)
    function read() {
      return $this->_tail(9);
    }
    
    // This is a methods to be propagated (See below)
    function write($txt='') {
      $box = $this->read();
      $box .= $txt . "\n";
      
      $fp = @fopen($this->chatFile, 'wb');
      @fwrite($fp, $box, strLen($box));
      @fclose($fp);
      return $box;
    }
  }
  
  // First create the object to be called. (Pseudo static)
  $theChatboxServer = new ChatboxServer();
  
  // Use the static $JsrsServer to 
  // propagate the methodes that are available for the client
  $JsrsServer->propagateMethod($theChatboxServer, 'read');
  $JsrsServer->propagateMethod($theChatboxServer, 'write');
  
  // Start the JsrsServer to handle the request from the client
  $JsrsServer->start();
?>