<?php
/*
When the CD query is sent from the JavaScript to the PHP page, the following happens:

   1. PHP creates an XML DOM object
   2. Find all <artist> elements that matches the name sent from the JavaScript
   3. Output the album information (send to the "txtHint" placeholder)
*/

$q=$_GET["q"];

$xmlDoc = new DOMDocument();
$xmlDoc->load("cd_catalog.xml");

$x = $xmlDoc->getElementsByTagName('ARTIST');
echo("$x");
for ($i=0; $i<=$x->length-1; $i++)
{
//Process only element nodes
$mynodetype = $x->item($i)->nodeType;
if ($mynodetype==1)
  {
  if ($x->item($i)->childNodes->item(0)->nodeValue == $q)
    {
    $y=($x->item($i)->parentNode);
    }
  }
}

$cd=($y->childNodes);

for ($i=0;$i<$cd->length;$i++)
{
//Process only element nodes
if ($cd->item($i)->nodeType==1)
  {
  echo("<b>" . $cd->item($i)->nodeName . ":</b> ");
  echo($cd->item($i)->childNodes->item(0)->nodeValue);
  echo("<br />");
  }
}
?> 