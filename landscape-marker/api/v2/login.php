<?php
session_start();
//logger:
include "../logger.php";
//eologger
if (!empty($_SESSION)) {
    if (in_array('username', $_SESSION)) {
        echo json_encode(["username" => $_SESSION['username']]);
        die();
    }
}
// sanitization:
function sanitize($item)
{
    return htmlspecialchars($item);
}

foreach ($_POST as $key => $value) {
    $_POST[$key] = sanitize($value);
}


// including db model:
include "../models/user.php";


header("Content-type: application/json");


$neededAttributes = ['username', 'password'];

foreach ($neededAttributes as $attr) {
    if (!in_array($attr, array_keys($_POST)) || trim($_POST[$attr]) == '') {
        echo json_encode(['error' => true, 'msg' => 'field ' . $attr . ' is empty']);
        die();
    }
}
$u = $User->getByUniqueValue('username', $_POST['username']);
if (!$u['error']) {
    $u = $u['msg'];
    if ($u['password'] == $_POST['password']) {
        echo json_encode(['error' => false, 'msg' => "user \"" . $_POST['username'] . ':', "values:" => [...$u]]);
        $_SESSION['username'] = $u['username'];
        $_SESSION['userid'] = $u['id'];
    } else {
        echo json_encode(['error' => true, 'msg' => "password is wrong!"]);
    }
} else {
    echo json_encode(['error' => true, 'msg' => "user \"" . $_POST['username'] . '" is non-existent!']);
}
