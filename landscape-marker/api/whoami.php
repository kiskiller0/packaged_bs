<?php

session_start();
include "../models/user.php";

$user = $User->getByUniqueValue('username', $_SESSION['username']);

if (empty($_SESSION) || !in_array('username', array_keys($_SESSION)) || $user['error']) {
    echo json_encode(['error' => true, 'msg' => 'not logged in!']);
    die();
}

$user = $user['msg'];

$neededFields = ['id', 'username', 'email', 'picture'];
$neededInfo = [];

foreach ($neededFields as $field) {
    $neededInfo[$field] = $user[$field];
}

echo json_encode(['error' => false, 'user' => $neededInfo]);