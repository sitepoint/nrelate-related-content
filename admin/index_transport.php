<?php

// Takes domain (user's home url) from POST and sends information to nrelate server
// Echos back $data which contains message from the server about current status of site indexing
	$domain = $_POST['domain'];
	$admin_version = $_POST['admin_version'];
	$curlPost = 'domain='.$domain;
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, 'http://api.nrelate.com/common_wp/'.$admin_version.'/indexcheck.php'); 
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $curlPost); 
	$data = curl_exec($ch);
	echo $data;
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