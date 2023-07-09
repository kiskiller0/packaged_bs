<?php
header("content-type: application/json");

include "../models/post.php";
include "../models/user.php";


if (!in_array('id', array_keys($_POST))) {
    // I will sort this mess asap inchallah, the json anatomy needs to be universal
    $posts = $post->getLastInserted();

    if ($posts['error']) {
        die(json_encode($posts));
    }

    $posts = $posts['msg'];
} else {
    $posts = $post->getByFieldBatched('id', $_POST['id'], LT);
    // TODO: []- check if id is a legit number
    // also, if the absence of id from posts yields null, than, we won't need an else clause.

    if ($posts['error']) {
        die(json_encode($posts));
    }

    $posts = $posts['data'];
}


$postsWithUserData = array();

foreach ($posts as $currentPost) {
    $user = $User->getByUniqueValue('id', $currentPost['userid'])['msg'];
    array_push($postsWithUserData, [...$currentPost, 'user' => ['username' => $user['username'], 'picture' => $user['picture']]]);
}

echo json_encode(['error' => false, 'posts' => $postsWithUserData]);

