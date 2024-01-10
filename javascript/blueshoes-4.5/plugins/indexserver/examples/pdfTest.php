<?php
/**
* @package    plugins_indexserver
* @subpackage examples
*/



// The following determines the document to search in.
$theDocument = "C:/usr/local/lib/php/blueshoes-4.2/documentation/howtos/Bs_FileCache.pdf";

// The text to search for. Usually we get this as a result of a form submit.
$searchText = "howto";

// First we read the document into memory space.
// Also, pdf documents can be read from a database or otherwise
// (which is what I did when writing the class).
$fp = fopen($theDocument, "r");
$content = fread($fp, filesize($theDocument));
fclose($fp);

// Allocate class instance
$pdf = new pdf_search($content);

// And do the search
if ($pdf->textfound($searchText)) {
	echo "We found $searchText.";
} else {
  echo "$searchText was not found.";
}


/**********************************************************************
**
** A class to search text in pdf documents.
** Not pretending to be useful other than that.
** But it can easily be extended to a full featured pdf document
** parser by anyone who chooses so.
**
** Author: Rene Kluwen / Chimit Software <rene.kluwen@chimit.nl>
**
** License: Public Domain
** Warranty: None
**
***********************************************************************/

class pdf_search {

        // Just one private variable.
        // It holds the document.
        var $_buffer;

        // Constructor. Takes the pdf document as only parameter
        function pdf_search($buffer) {
                $this->_buffer = $buffer;
        }

        // This function returns the next line from the document.
        // If a stream follows, it is deflated into readable text.
        function nextline() {
                $pos = strpos($this->_buffer, "\r");
                if ($pos === false) {
                        return false;
                }
                $line = substr($this->_buffer, 0, $pos);
                $this->_buffer = substr($this->_buffer, $pos + 1);
                if ($line == "stream") {
                        $endpos = strpos($this->_buffer, "endstream");
                        $stream = substr($this->_buffer, 1, $endpos - 1);
                        $stream = @gzuncompress($stream);
                        $this->_buffer = $stream . substr($this->_buffer, $endpos + 9);
                }
                return $line;
        }

        // This function returns the next line in the document that is printable text.
        // We need it so we can search in just that portion.
        function textline() {
                $line = $this->nextline();
                if ($line === false) {
                        return false;
                }
                if (preg_match("/[^\\\\]\\((.+)[^\\\\]\\)/", $line, $match)) {
                        $line = preg_replace("/\\\\(\d+)/e", "chr(0\\1);", $match[1]);
                        return stripslashes($line);
                }
                return $this->textline();
        }

        // This function returns true or false, indicating whether the document contains
        // the text that is passed in $str.
        function textfound($str) {
                while (($line = $this->textline()) !== false) {
                        if (preg_match("/$str/i", $line) != 0) {
                                return true;
                        }
                }
                return false;
        }
}

?>