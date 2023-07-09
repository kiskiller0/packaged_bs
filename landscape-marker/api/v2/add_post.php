<?php
die(json_encode($_POST));

session_start();


header('content-type: application/json');
include "../models/post.php";

if (!in_array('username', array_keys($_SESSION))) {
    if (!in_array('userid', array_keys($_SESSION))) {
        // #TODO: []-this is assumed redundant, and is gonna be cut in future code reviews!
        die(json_encode(['error' => true, 'msg' => 'not logged in!']));
    }
}


$needed_fields = ['content'];

// checking the presence of all fields
// and sanitizing the $_POST array
foreach ($needed_fields as $field) {
    if (!in_array($field, array_keys($_POST)) || trim($_POST[$field]) == '') {
        die(json_encode(['error' => true, 'msg' => $field . ' does not exists']));
    }
    $_POST[$field] = htmlspecialchars($_POST[$field]);
}


if (!in_array('imgsrc', array_keys($_FILES)) || !$_FILES['imgsrc']['type'] || count($_FILES['imgsrc']) < 2) {
    echo json_encode(['error' => true, 'msg' => 'no image selected!']);
    die();
} else {

    $allowed_types = ['jpeg', 'jpg', 'png'];
    $type = explode('/', $_FILES['imgsrc']['type'])[1];

    if (in_array($type, $allowed_types)) {
        // first, create the post:
        $lastPost = $post->addNew([...$_POST, 'userid' => $_SESSION['userid']]);
        if ($lastPost['error']) {
            die(json_encode(['error' => true, 'msg' => 'post not created!']));
        }

        $lastPost = $post->getLastInserted(1);

        $fpath = "../public/posts/";
        $fpath .= $lastPost['error'] ? 1 : (int)$lastPost['msg']['id'];

        $source = $_FILES['imgsrc']['tmp_name'];

        if (!move_uploaded_file($source, $fpath)) {
            die(json_encode(['error' => true, 'msg' => 'could not upload picture']));
        } else {
            header("location: ../..");
        }
    }
}





