<?php
// this should be in views


if (!empty($_GET) && in_array('search', array_keys($_GET))) {
    echo "you want to look for '" . $_GET['search'] . "'<br><hr>";
    if (strlen($_GET['search']) < 3) {
        die('search starts from 3 letters!');
    }
} else {
    echo "screw you!";
    die();
}

include "../views/partials/header.html";

include "../models/user.php";
include "../models/post.php";


function filterUsers($item)
{
    $neededTraits = ['username', 'picture', 'id'];
    $tmpItem = [];
    foreach ($neededTraits as $trait) {
        $tmpItem[$trait] = $item[$trait];
    }
    return $tmpItem;
}

function filterPosts($item)
{
    $neededTraits = ['content', 'id'];
    $tmpItem = [];
    foreach ($neededTraits as $trait) {
        $tmpItem[$trait] = $item[$trait];
    }
    return $tmpItem;
}

// removing unneeded fields, such as PASSWORD!
$users = array_map('filterUsers', $User->getUsernameLike($_GET['search']));
$posts = array_map('filterPosts', $post->getTitleLike($_GET['search']));

//var_dump($users);
//var_dump($posts);
?>
    <link rel="stylesheet" href="../../views/style/search.css">
    <title>Search:</title>
    <body>
    <div class="parent">
        <div class="posts">
            <h2>posts:</h2>
            <?php
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
        </div>
        <div class="users">
            <h2>users</h2>

            <?php
            foreach ($users as $u) {
                echo sprintf("<a href='../../views/user.php?id=%s'>", $u['id']);
                echo sprintf("<img src='%s' alt='%s\'s profile picture'/>", $u['picture'] ? '../../public/profiles/' . $u['username'] : '../../public/profiles/default/user.png', $u['username']);
                echo sprintf("<p>%s</p>", $u['username']);
                echo sprintf("</a>");
                echo "<hr>";
            }
            ?>
        </div>
    </div>
    </body>

<?php

include "../views/partials/footer.html";


