<?php

require_once $_SERVER['DOCUMENT_ROOT'] . "/models/table.php";

// table creation is going to be done here:
$Place = new Table('place', ['name', 'description', 'userid', 'latitude', 'longitude'], ['id', 'name']);
