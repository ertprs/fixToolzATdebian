<?

/* gets the data from a URL */
function get_data($url)
{
	$ch = curl_init();
	$timeout = 5;
	curl_setopt($ch,CURLOPT_URL,$url);
	curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
	curl_setopt($ch,CURLOPT_CONNECTTIMEOUT,$timeout);
	$data = curl_exec($ch);
	curl_close($ch);
	return $data;
}

$message = get_data('http://www.toolszone.ro/newsletter.php');
$css = get_data('http://www.toolszone.ro/stylesheet.css');

$message = '<style type="text/css">' . $css . '</style>' . $message;

$message = str_replace('images/','http://www.toolszone.ro/images/',$message);

$message = str_replace('http://www.toolszone.ro/newsletter.php','http://www.toolszone.ro/index.php',$message);

$to  = 'c.coman@yahoo.com';

// subject
$subject = 'ToolsZone.ro';


// To send HTML mail, the Content-type header must be set
$headers  = 'MIME-Version: 1.0' . "\r\n";
$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";

// Additional headers
$headers .= 'To: Catalin Coman <c.coman@yahoo.com>' . "\r\n";
$headers .= 'From: ToolsZone.ro <office@toolszone.ro>' . "\r\n";
$headers .= 'Cc: ' . "\r\n";
$headers .= 'Bcc: ' . "\r\n";

// Mail it
if(mail($to, $subject, $message, $headers)) echo $message;
?>
