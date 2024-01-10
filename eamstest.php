<?php
$url1="https://eams.dwc.ca.gov";
$url2="https://eams.dwc.ca.gov/public/GeneralPublic";

$ckfile = tempnam ("/", "CURLCOOKIE"); 
$ch = curl_init ($url1);
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
$castring=getcwd() . "/BuiltinObjectToken-VerisignClass3PublicPrimaryCertificationAuthority-G2.crt";
curl_setopt($ch, CURLOPT_CAINFO, $castring);
curl_setopt ($ch, CURLOPT_COOKIEJAR, $ckfile);
curl_setopt ($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPGET, true);
$output = curl_exec ($ch);

$ch = curl_init ($url2);
$fields = array("keyCaseNumber"=>"",
				"keyLastName" => "SMITH",
				"keyFirstName" => "JOHN",
        		"keyDateofBirth" => "",
        		"keyInjuiryDate" => "",
				"keyAddressLine1" => "",
              	"keyCity" => "",
              	"keyZip" => "",
              	"keyEmployerName" => "",
              	"__o3btn.CTL1" => "Search",
			  );
//url-ify the data for the POST
foreach($fields as $key=>$value) { $fields_string .= $key.'='.urlencode($value).'&'; }
$fields_string=rtrim($fields_string,'&');
// Initialize session and set URL.
curl_setopt($ch, CURLOPT_POST, count($fields));
curl_setopt($ch, CURLOPT_POSTFIELDS, $fields_string);
curl_setopt ($ch, CURLOPT_COOKIEJAR, $ckfile);
curl_setopt ($ch, CURLOPT_RETURNTRANSFER, true);
// Initialize session and set URL.
curl_setopt($ch,    CURLOPT_AUTOREFERER,         true);
curl_setopt($ch,    CURLOPT_FAILONERROR,         false);
curl_setopt($ch,    CURLOPT_FOLLOWLOCATION,        false);
curl_setopt($ch,    CURLOPT_HEADER,             true);
curl_setopt($ch,    CURLOPT_CONNECTTIMEOUT,     30);
$output = (curl_exec ($ch));
curl_close($ch);
// scrape output
$chunk1=strstr($output,"N2201");
$chunk1=strstr($chunk1,"<tbody>");
$strlen=strlen($chunk1);
$str=strpos($chunk1,'<tbody>');
$end=strpos($chunk1,'</tbody>')+8;
$len = $end-$str;
$tr = substr($chunk1, $str, $len);
?>

<table>
		<th>LAST</th>
		<th>FIRST</th>
		<th>CASE</th>
		<th>DOI</th>
		<th>EMPLOYER</th>
		<th>STATUS</th>
		<th>NEXT ACTION DATE</th>
		<?php echo($tr); ?>
</table>
