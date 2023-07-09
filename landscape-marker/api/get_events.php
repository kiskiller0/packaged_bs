<?php
header("content-type: application/json");

include "../models/event.php";
include "../models/user.php";


if (!in_array('id', array_keys($_POST))) {
    $places = $Event->getLastInserted();

    if ($places['error']) {
        die(json_encode($places));
    }

    $places = $places['msg'];
} else {
    $places = $Event->getByFieldBatched('id', $_POST['id'], LT);

    if ($places['error']) {
        die(json_encode($places));
    }

    $places = $places['data'];
    // TODO: []- check if id is a legit number
    // also, if the absence of id from posts yields null, than, we won't need an else clause.
}

$placesWithUserData = array();

foreach ($places as $currentPost) {
    $user = $User->getByUniqueValue('id', $currentPost['userid'])['msg'];
    array_push($placesWithUserData, [...$currentPost, 'user' => ['username' => $user['username'], 'picture' => $user['picture']]]);
    // array_push($placesWithUserData, json_encode([...$currentPost, 'username' => $username]));
}

echo json_encode(['error' => false, 'posts' => $placesWithUserData]);

//echo json_encode(['error' => false, 'posts' => $post->getLastPosts($_POST['id'])]);
