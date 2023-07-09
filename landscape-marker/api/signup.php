<?php
header("Content-type: application/json");
//logger:
include "../logger.php";
//eologger

// including db model:
include "../models/user.php";



if (empty($_POST)) {
    echo json_encode(["error" => true, "msg" => "no data supplied!"]);
    die(); // prevent multiple fetch replies
}

// sanitization:
function sanitize($item)
{
    return htmlspecialchars($item);
}

foreach ($_POST as $key => $value) {
    $_POST[$key] = sanitize($value);
}
//
// preparing profile picture upload:
$allowedTypes = ['jpeg', 'jpg', 'png'];
$path = "../public/profiles/";

$picture = false; //this boolean is to know whether we received an image for the profile

if (!empty($_FILES)) {
    try {
        if ($_FILES['picture']['type'] !== '') {
            $extension = explode("/", $_FILES['picture']['type'])[1];
            $tmpProfile = $_FILES["picture"]["tmp_name"];
            if (in_array($extension, $allowedTypes)) {
                // move_uploaded_file($tmpProfile, $path . $_POST['username'] . '.' . $extension);
                move_uploaded_file($tmpProfile, $path . $_POST['username']);
                // echo json_encode(["error" => false, "msg" => "file accepted!"]);
                $picture = true;
            }
        }
    } catch (Exception $err) {
    }
}

echo json_encode($User->addNew(['picture' => $picture, ...$_POST]));
