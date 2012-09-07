<?php
/*********************************************************************
    open.php

    New tickets handle.

    Sunny Gulati <sunnyg246@gmail.com>

    Released under the GNU General Public License WITHOUT ANY WARRANTY.

**********************************************************************/
require('client-txtweb.inc.php');
define('SOURCE','Web'); //Ticket source.
$inc='open-txtweb.inc.php';    //default include.
$errors=array();
$isChecked = true;
if($_GET):
    $_GET['deptId']=$_GET['emailId']=0; //Just Making sure we don't accept crap...only topicId is expected.
	
$inputs=explode(",",$_GET["txtweb-message"],3); //considerd space as delimiter]
if($inputs[0] == "" || $inputs[1] == "" || $inputs[2] == "")
	$isChecked = false;

if($isChecked)
{
unset($_GET['name']);
$_GET['name'] = $inputs[0];

unset($_GET['email']);
$_GET['email'] = $inputs[1];

unset($_GET['message']);
$_GET['message'] = $inputs[2];

unset($_GET['subject']);
$_GET['subject'] = "txtweb ticket"; //substr($inputs[2],0,20);




    //Ticket::create...checks for errors..
    if(($ticket=Ticket::create($_GET,$errors,SOURCE))){
	
		$ch = curl_init();
		$ch1 = curl_init();
		$txtweb_mobile_admin="ADMIN_MOBILE_HASH";
		$txtweb_mobile_user=$_REQUEST['txtweb-mobile'];
		$msgtxt1 = "Subject :".$_GET['subject']." Name :".$_GET['name']." Message : ".$_GET['message']. " Email : ".$_GET['email'];
		$msgtxt="Thanks ".$_POST['name']." for contacting".HOSTURL." . We will get back to you soon";
		curl_setopt($ch,CURLOPT_URL,  "http://".HOSTURL."/push-txtweb.php");
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, "txtweb-mobile=$txtweb_mobile_user&txtweb-message=$msgtxt");
		$buffer = curl_exec($ch);
		curl_close($ch);
		curl_setopt($ch1,CURLOPT_URL, "http://".HOSTURL."/push-txtweb.php");
		curl_setopt($ch1, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch1, CURLOPT_POST, 1);
		curl_setopt($ch1, CURLOPT_POSTFIELDS, "txtweb-mobile=$txtweb_mobile_admin&txtweb-message=$msgtxt1");
		$buffer1 = curl_exec($ch1);
		curl_close($ch1);
        $msg='Support ticket request created';
		
     //   if($thisclient && $thisclient->isValid()) //Logged in...simply view the newly created ticket.
           // @header('Location: tickets.php?id='.$ticket->getExtId());
        //Thank the user and promise speedy resolution!
        }else{
      //  $errors['err']=$errors['err']?$errors['err']:'Unable to create a ticket. Please correct errors below and try again!';
	  $msg='Wrong Format. Try Again!';
    }
}
else
{
  $msg='Wrong Format. Try Again!';
}
	
endif;
?>
<html>
<head>
<title> Hello! </title>
<meta http-equiv='Content-Type' content='text/html; charset=UTF-8' />
<meta name='txtweb-appkey' content='YOUR_SERVICE_KEY' />
</head>
<body>
<?php
//page
//require(CLIENTINC_DIR.'header.inc.php');
require(CLIENTINC_DIR.$inc);
//require(CLIENTINC_DIR.'footer.inc.php');
?>
</body>
</html>