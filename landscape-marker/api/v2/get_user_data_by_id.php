<?php

header('Content-type: application/json');

if (!in_array('id', array_keys($_POST)) || trim($_POST['id']) == '') {
    die(json_encode(['error' => true, 'msg' => 'no id is sent!']));
}

require_once "../models/user.php";

$needed_fields = ['picture', 'username'];
echo json_encode($User->getByUniqueValue('id', $_POST['id']));
