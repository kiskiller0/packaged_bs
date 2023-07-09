<?php
session_start();

header('content-type: application/json');

include "../models/place.php";

if (!in_array('username', array_keys($_SESSION))) {
    if (!in_array('userid', array_keys($_SESSION))) {
        // #TODO: []-this is assumed redundant, and is gonna be cut in future code reviews!
        die(json_encode(['error' => true, 'msg' => 'not logged in!']));
    }
}


$needed_fields = ['name', 'description', 'latitude', 'longitude']; // the needed_fields field does contain userid

foreach ($needed_fields as $field) {
    if (!in_array($field, array_keys($_POST)) || trim($_POST[$field]) == '') {
        die(json_encode(['error' => true, 'msg' => $field . ' does not exists']));
    }
    $_POST[$field] = htmlspecialchars($_POST[$field]);
}

if (!in_array('imgsrc', array_keys($_FILES)) || $_FILES['imgsrc']['tmp_name'] == '') {
    die(json_encode(['error' => true, 'msg' => 'no img supplied for the place!']));
}


if (!in_array('imgsrc', array_keys($_FILES)) || !$_FILES['imgsrc']['type'] || count($_FILES['imgsrc']) < 2) {
    die(json_encode(['error' => true, 'msg' => 'no image selected!']));
} else {

    $allowed_types = ['jpeg', 'jpg', 'png'];
    $type = explode('/', $_FILES['imgsrc']['type'])[1];
    if (in_array($type, $allowed_types)) {
        $result = $Place->addNew([...$_POST, 'userid' => $_SESSION['userid']]);
        if ($result['error']) {
            die(json_encode($result));
        }

        $lastPost = $Place->getLastInserted(1);

        if ($lastPost['error']) {
            die(json_encode($lastPost));
        }

        $lastPost = $lastPost['msg'];
        $fpath = sprintf("../public/places/%s", $lastPost['id']);
        if (!move_uploaded_file($_FILES['imgsrc']['tmp_name'], $fpath)) {
            die(json_encode(['error' => true, 'msg' => 'could not upload picture']));
        } else {
            echo (json_encode(['error' => false, 'msg' => 'success']));
        }
    }
}
