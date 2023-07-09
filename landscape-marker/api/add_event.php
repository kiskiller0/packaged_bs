<?php

session_start();

header('content-type: application/json');


include "../models/event.php";
include "../models/place.php";

if (!in_array('username', array_keys($_SESSION))) {
    if (!in_array('userid', array_keys($_SESSION))) {
        // #TODO: []-this is assumed redundant, and is gonna be cut in future code reviews!
        die(json_encode(['error' => true, 'msg' => 'not logged in!']));
    }
}


$needed_fields = ['name', 'description', 'date', 'placeid']; // the needed_fields field does contain userid

foreach ($needed_fields as $field) {
    if (!in_array($field, array_keys($_POST)) || trim($_POST[$field]) == '') {
        die(json_encode(['error' => true, 'msg' => $field . ' does not exists']));
    }
    $_POST[$field] = htmlspecialchars($_POST[$field]);
}

if (!in_array('imgsrc', array_keys($_FILES)) || $_FILES['imgsrc']['tmp_name'] == '') {
    die(json_encode(['error' => true, 'msg' => 'no img supplied for the event!']));
}

// checking the existence of the place:
$place = $Place->getByUniqueValue("id", $_POST['placeid']);

if ($place['error']) {
    die(json_encode(['error' => true, 'msg' => 'place non-existent']));
}

if (!in_array('imgsrc', array_keys($_FILES)) || !$_FILES['imgsrc']['type'] || count($_FILES['imgsrc']) < 2) {
    die(json_encode(['error' => true, 'msg' => 'no image selected!']));
} else {

    $allowed_types = ['jpeg', 'jpg', 'png'];
    $type = explode('/', $_FILES['imgsrc']['type'])[1];

    if (in_array($type, $allowed_types)) {

        $result = $Event->addNew([...$_POST, 'userid' => $_SESSION['userid']]);

        if ($result['error']) {
            die(json_encode($result));
        }

        $lastEvent = $Event->getLastInserted(1);

        if ($lastEvent['error']) {
            die(json_encode($lastEvent));
        }

        $lastEvent = $lastEvent['msg'];
        $fpath = sprintf("../public/events/%s", $lastEvent['id']);
        if (!move_uploaded_file($_FILES['imgsrc']['tmp_name'], $fpath)) {
            die(json_encode(['error' => true, 'msg' => 'could not upload picture']));
        } else {
            echo (json_encode(['error' => false, 'msg' => 'success']));
        }
    }
}


header('location: ../..');
