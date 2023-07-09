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
    <link rel="stylesheet" href="../../views/style/thread.css">
    <title>Post: <?php echo $_GET['id'] || "undefined!" ?></title>
</head>
<body>


<?php


require_once $_SERVER['DOCUMENT_ROOT'] . "/models/comment.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/models/post.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/models/user.php";

$wantedPost = $post->getByUniqueValue('id', $_GET['id']);

if ($wantedPost['error']) {
    die(json_encode(['error' => true, 'msg' => 'missing post!']));
}

$comments = $Comment->getByField("postid", $_GET['id'], EQ);

$wantedPost = $wantedPost['msg'];
//var_dump($wantedPost);

// getting user info from userid:
$poster = $User->getByUniqueValue('id', $wantedPost['userid']);

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


//echo sprintf(
//    "<div class='userDiv'>
//                <div class='userImg'>
//                    <img src='%s' alt='user profile picture'>
//                </div>
//                <div class='username'>%s</div>
//            </div>"
//    ,
//    $poster['picture'] ? '../../public/profiles/' . $poster['username'] : '../../public/profiles/default/user.png',
//    $poster['username']);

echo sprintf("<p class='postContent'>%s</p>", $wantedPost['content']);
echo sprintf("<img  src='%s' alt='postImg'>", '../../public/posts/' . $wantedPost['id']);
echo sprintf("<p class='postDate'>%s</p>", $wantedPost['date']);

echo "</div>";

echo "<hr>";
?>

<div class="add_comment">
    <form action="../../api/add_comment.php" method="post">
        <textarea name="content" cols="30" rows="10"></textarea>
        <input type="submit" value="send">
    </form>
</div>

<div class="comments">
    <?php
    function renderComment($comment)
    {
        echo '<div class="comment">';
        echo "<div class='userDiv'>";
        echo "<img alt='an image of the user'/>";
        echo "<p class='username'></p>";
        echo "</div>";
        echo sprintf("<p class='content'>%s</p>", $comment['content']);
        echo sprintf("<p class='by'>%s</p>", $comment['userid']);
        echo sprintf("<p class='date'>at: %s</p>", $comment['date']);
        echo '</div>';
    }

    if (!$comments['error'] && count($comments['msg'])) {
        // show all comments:
        foreach ($comments['msg'] as $comment) {
            renderComment($comment);
        }
    } else {
        if (in_array('userid', array_keys($_SESSION)) && $_SESSION['userid'] == $wantedPost['userid']) {
            echo "<p>no comments yet! you subscribe to our advertisment service, give us money, and we give you traffic! win-win!</p>";
        } else {
            echo "no comments, be the first!";
        }
    }
    ?>
</div>

</body>
<script src="./scripts/post.js"></script>
</html>
