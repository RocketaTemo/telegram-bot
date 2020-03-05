<?php

/**
 * Use this file for webhook. It will reply any command from users
 * 
 */

require_once("init.php");

$bot = new TestBot();
$bot->replyCommand();

?>