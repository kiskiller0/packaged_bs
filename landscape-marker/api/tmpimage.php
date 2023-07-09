<?php
//logger:
include "../logger.php";
//eologger

// this is a fetch api endpoint, reply with json!
// make the generated name random (a security feature)
$allowedTypes = ['jpeg', 'jpg', 'png'];
$path = "../public/tmpprofiles/";
if (!empty($_FILES)) {
    $ext = explode("/",  $_FILES['picture']['type'])[1];
    if (!in_array($ext, $allowedTypes)) {
        die(json_encode(["error" => true, 'msg' => 'unspported file type ' . $ext]));
    }
    $newPath = $path . "tmp" . '.' . $ext;
    move_uploaded_file($_FILES['picture']['tmp_name'], $newPath);
    echo json_encode(["tmpImg" => $newPath]);
    // echo "image uploaded successfully! to <img src=\"" . $newPath . "\">";
} else {
    echo json_encode(["tmpImg" => false]);
}
