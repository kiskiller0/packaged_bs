<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="../../views/style/user.css">


    <?php
    // this should be in views

    if (empty($_GET) && in_array('id', array_keys($_GET))) {
        die("id not given!");
    }

    include "../views/partials/header.html";

    include "../models/user.php";
    include "../models/post.php";


    // removing unneeded fields, such as PASSWORD!
    $user = $User->getByUniqueValue('id', $_GET['id']);

    if ($user['error']) {
        die(json_encode($user));
    }
    $user = $user['msg'];

    ?>
    <?php echo sprintf("<title>%s's space</title>", $user['username'] ? $user['username'] : 'error'); ?>
</head>
<body>
<?php

echo "<div class='user'>";
echo sprintf("<img src='%s' alt='%s\'s profile picture'/>", $user['picture'] ? '../../public/profiles/' . $user['username'] : '../../public/profiles/default/user.png', $user['username']);
echo sprintf("<p>%s</p>", $user['username']);
echo "</div>";
echo "<hr>";

$posts = $post->getByField('userid', $_GET['id'], EQ);

if ($posts['error']) {
    die("<p>There is no posts!</p>");
}

$posts = $posts['msg'];

foreach ($posts as $p) {
    echo sprintf("<a href=\"../../views/post.php?id=%s\">", $p['id']);
    echo "<div class=\"post\">";
    echo "<img src=\"../../public/posts/" . $p['id'] . "\" />";
    echo "<p>" . $p['content'], '</p>';
    echo "</div>";
    echo sprintf("</a>");
    echo "<hr>";
}

?>
</body>
</html>