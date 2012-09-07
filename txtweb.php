<html>
<head>
<title> Hello! </title>
<meta http-equiv='Content-Type' content='text/html; charset=UTF-8' />
<meta name='txtweb-appkey' content='YOUR_SERVICE_KEY' />
</head>
<body>
<?php   
if($_REQUEST['compliant'] || $_REQUEST['type'])
{
include "open-txtweb.php";
}
else if($_REQUEST['status'])
{
include "status-txtweb.php";
} 
else
{ ?>
<a href="./txtweb.php?compliant=true" class="txtweb-menu-for">To Register a Complaint</a><br />
<a href="./txtweb.php?status=true" class="txtweb-menu-for">To Know the Status</a><br />
<?php
}
?>
</body>
</html>
