<?php
if(!defined('OSTCLIENTINC')) die('Kwaheri rafiki!'); //Say bye to our friend..

?>
<?php if($_REQUEST['type']) {
 ?>
Welcome to osTicket<br /><br />
To Register a Ticket<br />
<form action="./open-txtweb.php" method="get" class="txtweb-form">
Name,Email,Message<input type="text" name="txtweb-message" size="25"> <br />
For Ex: abc,xyz@xyz.com,message here
<input type="hidden" name="name" value="">
<input type="hidden" name="email" value="" />
<input type="hidden" name="phone" value="" />
<input type="hidden" name="phone_ext" value="" /> 
<input type="hidden" name="topicId" value="<?php echo $_REQUEST['type'] ?>" /> 
<input type="hidden" name="subject"  value="" />
<input type="hidden" name="message"  value="" />
<input class="button" type="submit" name="submit_x" value="Submit">
</form>

<?php } else if($_REQUEST['txtweb-message']) { ?>
    <?if($errors['err']) {?>
        <?=$errors['err']?>
    <?}elseif($msg) {?>
        <?=$msg?>
    <?}elseif($warn) {?>
        <?=$warn?>
    <?}?>
<?php } else { ?>
Select Topic:
<?
    $services= db_query('SELECT topic_id,topic FROM '.TOPIC_TABLE.' WHERE isactive=1 ORDER BY topic');
    if($services && db_num_rows($services)) {
    while (list($topicId,$topic) = db_fetch_row($services)){ ?>
	<a href="./txtweb.php?type=<?=$topicId?>" class="txtweb-menu-for"><?=$topic?></a><br />
    <?
    }
   }else{?>
   	<a href="./txtweb.php?type=0" class="txtweb-menu-for">General Inquiry</a><br />
                <?}?>


<?php } ?>
