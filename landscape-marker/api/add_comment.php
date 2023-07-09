<?php
session_start();


if (!in_array('userid', array_keys($_SESSION)) || trim($_SESSION['username']) == '') {
    die(json_encode(['error' => true, 'msg' => 'not logged in!']));
}

$requiredFields = ['postid', 'content'];

// validation and sanitization:
foreach ($requiredFields as $field) {
    if (!in_array($field, array_keys($_POST)) || trim($_POST[$field]) == '') {
        die(json_encode(['error' => true, 'msg' => "missing field: $field"]));
    }
    // sanitize them here:
    $_POST[$field] = htmlspecialchars($_POST[$field]);
}


require_once $_SERVER['DOCUMENT_ROOT'] . "/models/comment.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/models/post.php";


$postData = $post->getByUniqueValue('id', $_POST['postid']);

if ($postData['error']) {
    die(json_encode(['error' => true, 'msg' => "missing post with id: " . $_POST['postid']]));
}

die(json_encode($Comment->addNew([...$_POST, 'userid' => $_SESSION['userid']])));
