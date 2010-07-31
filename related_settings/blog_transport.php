

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
	logData1('INIT');
	$domain = $_POST['domain'];
	logData1($domain);
	$curlPost = 'domain='.$domain;
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, 'http://api.nrelate.com/blogcheck.php'); 
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