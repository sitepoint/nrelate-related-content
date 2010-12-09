<?php
$debug=false;
function logData1($data) {
     global $debug;
     $data.= "\r\n";
     if ($debug) {
         if( $fh = @fopen( 'transport.log', 'a+' ) ) {
             $now=gmstrftime("%Y-%m-%d %H:%M:%S",time()).': ';
             $req= $_SERVER['REMOTE_ADDR'].': ';
             fputs( $fh, $now, strlen($now) );
             fputs( $fh, $req, strlen($req) );
             fputs( $fh, $data, strlen($data) );
             fclose( $fh );
        }
    }
}
// Takes domain (user's home url) and adcode from POST and sends information to nrelate server
// Echos back $data which contains message from the server about ad validation
	logData1('INIT');
	$domain = $_POST['domain'];
	$adcode = $_POST['adcode'];
	$adminversion = $_POST['adminversion'];
	logData1($domain);
	switch ($adcodeopt){
	case true:
		$ad = 1;
		break;
	default:
		$ad = 0;
	}
	$curlPost = 'domain='.$domain.'&adcode='.$adcode;
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, 'http://api.nrelate.com/common_wp/'.$adminversion.'/adcheck.php'); 
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $curlPost); 
	$data = curl_exec($ch);
	echo $data;
	logData1("Data Received: ".$data);
	$info = curl_getinfo($ch);
	switch ($info['http_code']){
		case 200:
			return "Success";
			break;
		default:
			return "Error accessing the nrelate server.";
			break;
	}
	curl_close($ch);

	
?>