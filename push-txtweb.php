<?php
require_once 'class.xhttp.php';

function xml2assoc($xml) {
        $tree = null;
        while($xml->read())
                switch ($xml->nodeType) {
                        case XMLReader::END_ELEMENT: return $tree;
                        case XMLReader::ELEMENT:
                                                     $node = array('tag' => $xml->name, 'value' => $xml->isEmptyElement ? '' : xml2assoc($xml));
                                                     if($xml->hasAttributes)
                                                             while($xml->moveToNextAttribute())
                                                                     $node['attributes'][$xml->name] = $xml->value;
                                                     $tree[] = $node;
                                                     break;
                        case XMLReader::TEXT:
                        case XMLReader::CDATA:
                                                     $tree .= $xml->value;
                }
        return $tree;
}

function pushMessage($mobilehash,$message,$appkey,$pubkey) {
	$head = "<html><head><title>push message</title><meta name='txtweb-appkey' content='$appkey'/></head><body>";
	$tail = "</body></html>";
	$message = $head.$message.$tail;
	$data = array();
	$data['post'] = array(
			'txtweb-mobile' => $mobilehash,
			'txtweb-message' => stripslashes($message),
			'txtweb-pubkey' => $pubkey,
			);
	$response = xhttp::fetch("http://api.txtweb.com/v1/push", $data);

	
	//Check response from push api
	$r = new XMLReader();
	$r->xml($response['body']);
	$res = xml2assoc($r);
	
	$status = $res[0]['value'][0]['value'][0]['value'];
//	$message = 

	return $status; //0 => success, non-zero => push failed
}

$appkey = "YOUR_SERVICE_KEY";
$pubkey = "YOUR_PUBLISHER_KEY";
print "<html><head><title>push message</title><meta name='txtweb-appkey' content='$appkey'/></head><body>";

/*
 * An app to push a message to user who sends a message to this app
 * 
 * Example: 
 * @pushme hello
 * Response 1 : Trying to push a message to your mobile //App response
 * Response 2 : You sent "hello" //Push response
 */

//we will be using user's hash code to push message to user, if you want to push message to a specific user, you need to have user's hash code / you can store the hash code in a db and then read the values from db. 
$user_mobile = $_REQUEST['txtweb-mobile']; 

$user_message =  $_REQUEST['txtweb-message'];

print "Trying to push a message to your mobile";

$result = pushMessage($user_mobile, $user_message, $appkey,$pubkey);

if ($result == 0) {
	print "Message sent successfully!";
}

else if ( $result == -1) {
	//try again
	$try_result = pushMessage($user_mobile, $user_message, $appkey, $pubkey);
	print "Result after trying again ".$try_result ;
}

else {
	print "!!!Error occured!!!<br/>Error code : ".$result;
}

print "</body></html>";
?>