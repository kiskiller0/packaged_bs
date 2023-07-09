<?php
session_start();

if (!in_array('id', array_keys($_GET))) {
    die(json_encode(['error' => true, 'msg' => 'no id supplied!']));
}

?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <link
            rel="stylesheet"
            href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
            integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw=="
            crossorigin="anonymous"
            referrerpolicy="no-referrer"
    />

    <link rel="stylesheet" href="../../views/style/thread.css">
    <title>Navigate</title>
</head>
<body>


<?php

require_once $_SERVER['DOCUMENT_ROOT'] . "/models/comment.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/models/place.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/models/user.php";

$wantedPlace = $Place->getByUniqueValue('id', $_GET['id']);

if ($wantedPlace['error']) {
    die(json_encode(['error' => true, 'msg' => 'missing post!']));
}

//$comments = $Comment->getByField("postid", $_GET['id'], EQ);

$wantedPlace = $wantedPlace['msg'];
//var_dump($wantedPlace);

// getting user info from userid:
$poster = $User->getByUniqueValue('id', $wantedPlace['userid']);

if ($poster['error']) {
    die(json_encode(['error' => true, 'msg' => 'poster not found!']));
}

$poster = $poster['msg'];


echo "<div class='post'>";
echo sprintf(
    "<a href='%s'>
                <div class='userDiv'>
                    <div class='userImg'>
                        <img src='%s' alt='user profile picture'>
                    </div>
                <div class='username'>%s</div>
            </div></a>"
    , "../views/user.php?id=" . $poster['id'],
    $poster['picture'] ? '../../public/profiles/' . $poster['username'] : '../../public/profiles/default/user.png',
    $poster['username']);


echo "<p class='postContent'>Description:</p>";
echo sprintf("<p class='postContent'>%s</p>", $wantedPlace['description']);
echo sprintf("<img  src='%s' alt='postImg'>", '../../public/places/' . $wantedPlace['id']);
echo sprintf("<p class='postDate'>%s</p>", $wantedPlace['createdAt']);
echo sprintf("<a href='https://www.google.com/maps/@%s,%s' target='_blank'><i class=\"fa-sharp fa-solid fa-location-dot\"></i></a>", $wantedPlace['latitude'], $wantedPlace['longitude']);
echo sprintf("<p class='postContent'>id: %s</p>", $wantedPlace['id']);

echo "</div>";

echo "<hr>";
?>


</body>
<script src="./scripts/post.js"></script>
</html>
