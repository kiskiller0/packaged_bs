<?php

//if (!in_array('id', array_keys($_POST)) || trim($_POST['id']) == '') {
//    die(json_encode(['error' => true, 'msg' => 'you did not supply any qrcode!']));
//}
//die(json_encode(['error' => false, 'msg' => "your qrcode: " . $_POST['id']]));

//die(dirname(__FILE__));
var_dump($_SERVER);
die();

echo "test:<br>";

require_once "models/post.php";
require_once "models/user.php";


var_dump($post->getByField('id', 1, GT));