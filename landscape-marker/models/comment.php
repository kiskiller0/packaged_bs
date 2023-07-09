<?php


require_once $_SERVER['DOCUMENT_ROOT'] . "/models/table.php";

// table creation is going to be done here:

$Comment = new Table('comment', ['content', 'userid', 'postid'], ['id']);