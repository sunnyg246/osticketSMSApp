<?php if($_REQUEST['id'] && $ticket->getStatus() != "") { 
?>

Subject: <?=Format::htmlchars($ticket->getSubject())?><br />
Status : <?=$ticket->getStatus()?><br /><br />
			


    <?if($errors['err']) {?>
        <p align="center" id="errormessage"><?=$errors['err']?></p>
    <?}elseif($msg) {?>
        <p align="center" id="infomessage"><?=$msg?></p>
    <?}?>


        <?
	    //get messages
        $sql='SELECT msg.*, count(attach_id) as attachments  FROM '.TICKET_MESSAGE_TABLE.' msg '.
            ' LEFT JOIN '.TICKET_ATTACHMENT_TABLE.' attach ON  msg.ticket_id=attach.ticket_id AND msg.msg_id=attach.ref_id AND ref_type=\'M\' '.
            ' WHERE  msg.ticket_id='.db_input($ticket->getId()).
            ' GROUP BY msg.msg_id ORDER BY created';
	    $msgres =db_query($sql);
	    while ($msg_row = db_fetch_array($msgres)):
		    ?>
		<?=Format::db_daydatetime($msg_row['created'])?><br />
		<?=Format::display($msg_row['message'])?><br /><br />
            <?
            //get answers for messages
            $sql='SELECT resp.*,count(attach_id) as attachments FROM '.TICKET_RESPONSE_TABLE.' resp '.
                ' LEFT JOIN '.TICKET_ATTACHMENT_TABLE.' attach ON  resp.ticket_id=attach.ticket_id AND resp.response_id=attach.ref_id AND ref_type=\'R\' '.
                ' WHERE msg_id='.db_input($msg_row['msg_id']).' AND resp.ticket_id='.db_input($ticket->getId()).
                ' GROUP BY resp.response_id ORDER BY created';
            //echo $sql;
		    $resp =db_query($sql);
		    while ($resp_row = db_fetch_array($resp)) {
                $respID=$resp_row['response_id'];
                $name=$cfg->hideStaffName()?'staff':Format::htmlchars($resp_row['staff_name']);
                ?>
					<?=Format::db_daydatetime($resp_row['created'])?>&nbsp;-&nbsp;<?=$name?> <br />
			       <?=Format::display($resp_row['response'])?><br /><br />
		    <?
		    } //endwhile...response loop.
            $msgid =$msg_row['msg_id'];
        endwhile; //message loop.
     ?>

        <?if($_POST && $errors['err']) {?>
            <p align="center" id="errormessage"><?=$errors['err']?></p>
        <?}elseif($msg) {?>
            <p align="center" id="infomessage"><?=$msg?></p>
        <?}?>

        <?if($ticket->isClosed()) {?>
        Ticket will be reopened on message post
        <?}?>
        <form action="./status-txtweb.php" name="reply" method="post" class="txtweb-form">
			Message To Reply<input type="text" name="message" size="25"> <br />
            <input type="hidden" name="post_id" value="<?=$ticket->getExtId()?>">
            <input type="hidden" name="respid" value="<?=$respID?>">
            <input type="hidden" name="a" value="postmessage">
            <input value="submit" type='submit' value='Post Reply' />
        </form>

		<?php
		} else if($_REQUEST['post_id']){
		    $ticket= new Ticket(Ticket::getIdByExtId((int)$_REQUEST['post_id']));
		
            //Everything checked out...do the magic.
            if(($msgid=$ticket->postMessage($_POST['message'],'Web'))) {
                   
               echo $msg='Message Posted Successfully';
            }else{
               echo $errors['err']='Unable to post the message. Try again';
            }
        }
		else{
            echo 'Not a Valid Ticket. Please try again';
        }
		
		?>
