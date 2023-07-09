<?php
die(json_encode($_POST));

session_start();
//die(json_encode(['error' => false, 'msg' => $_SESSION]));
//die(json_encode($_POST));

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

//else {
//    die(json_encode(['error' => false, 'msg' => $_SESSION]));
//}


require_once $_SERVER['DOCUMENT_ROOT'] . "/models/comment.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/models/post.php";


$postData = $post->getByUniqueValue('id', $_POST['postid']);

if ($postData['error']) {
    die(json_encode(['error' => true, 'msg' => "missing post with id: " . $_POST['postid']]));
}

die(json_encode($Comment->addNew([...$_POST, 'userid' => $_SESSION['userid']])));

//die(json_encode(['error' => false, 'msg' => sprintf('post exist, inserting comment by %s:%s', $_SESSION['username'], $_POST['content']), 'data' => $postData]));

//return $Comment->addNew($_POST);