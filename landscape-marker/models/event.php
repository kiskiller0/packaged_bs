<?php

require_once $_SERVER['DOCUMENT_ROOT'] . "/models/table.php";

// table creation is going to be done here:
$Event = new Table('event', ['name', 'placeid', 'description', 'userid', 'date'], ['id']);
