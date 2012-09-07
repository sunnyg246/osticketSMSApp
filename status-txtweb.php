<?php
/*********************************************************************
    tickets.php

    Main client/user interface.
    Note that we are using external ID. The real (local) ids are hidden from user.

    Peter Rotich <peter@osticket.com>
    Copyright (c)  2006-2010 osTicket
    http://www.osticket.com

    Released under the GNU General Public License WITHOUT ANY WARRANTY.
    See LICENSE.TXT for details.

    vim: expandtab sw=4 ts=4 sts=4:
    $Id: $
**********************************************************************/
require('client-txtweb.inc.php');
define('SOURCE','Web'); //Ticket source.
//$inc='open-txtweb.inc.php';    //default include.
//Check if any id is given...
if(($id=$_REQUEST['id']?$_REQUEST['id']:$_POST['ticket_id']) && is_numeric($id) || $_REQUEST['post_id']) {
    //id given fetch the ticket info and check perm.
    $ticket= new Ticket(Ticket::getIdByExtId((int)$id));
            //Everything checked out.
        $inc='viewticket-txtweb.inc.php';

}
?>
<html>
<head>
<title> Hello! </title>
<meta http-equiv='Content-Type' content='text/html; charset=UTF-8' />
<meta name='txtweb-appkey' content='YOUR_SERVICE_KEY' />
</head>
<body>
<?php
 if($_REQUEST['status']) {
 ?>
 <form action="./status-txtweb.php" method="get" class="txtweb-form">
Ticket ID<input type="text" name="id" size="25"> <br />
</form>
<?php
 }

//include(CLIENTINC_DIR.'header.inc.php');
include(CLIENTINC_DIR.$inc);
//include(CLIENTINC_DIR.'footer.inc.php');
?>
</body>
</html>