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

require_once $_SERVER['DOCUMENT_ROOT'] . "/models/event.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/models/place.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/models/user.php";

$event = $Event->getByUniqueValue('id', $_GET['id']);

if ($event['error']) {
    die(json_encode(['error' => true, 'msg' => 'missing post!']));
}

//$comments = $Comment->getByField("postid", $_GET['id'], EQ);

$event = $event['msg'];
//var_dump($event);

// getting user info from userid:
$poster = $User->getByUniqueValue('id', $event['userid']);

if ($poster['error']) {
    die(json_encode(['error' => true, 'msg' => 'poster non-existent!']));
}

$poster = $poster['msg'];

$place = $Place->getByUniqueValue('id', $event['placeid']);

if ($place['error']) {
    die(json_encode(['error' => true, 'msg' => 'place not found!']));
}

$place = $place['msg'];


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
echo sprintf("<p class='postContent'>%s</p>", $event['description']);
echo sprintf("<img  src='%s' alt='postImg'>", '../../public/events/' . $event['id']);
echo sprintf("<p class='postDate'>deadline: %s</p>", $event['date']);
echo sprintf("<a href='../../views/place.php?id=%s' target='_blank'><i class=\"fa-sharp fa-solid fa-location-dot\"></i></a>", $place['id']);

//var_dump($place);

echo "</div>";

echo "<hr>";
?>


</body>
<script src="./scripts/post.js"></script>
</html>
