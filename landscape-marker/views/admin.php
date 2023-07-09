<?php
session_start();

if (!in_array('username', array_keys($_SESSION)) || $_SESSION['username'] != 'admin') {
    die(json_encode(['error' => true, 'msg' => 'you are not admin!']));
}
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <link rel="stylesheet" href="../views/style/admin.css">
    <title>Admin</title>
</head>
<body>
<h1>Welcome in the Lounge of Dictatorship, Hitler's office:</h1>
<?php

if (in_array('id', array_keys($_GET)) || in_array('table', array_keys($_GET))) {
    if (trim($_GET['id']) == '' || trim($_GET['table']) == '') {
        // import the dashboard (the code at the bottom
    }
}


require_once "../models/post.php";
require_once "../models/comment.php";
require_once "../models/place.php";
require_once "../models/user.php";
require_once "../models/event.php";


require_once "../views/classes/Renderer.php";


/** @var Table[] $tables */
$tables = [$post, $Place, $Comment, $User, $Event];

/** @var Rendering/Renderer[] $renderers */
$renderers = [];

foreach ($tables as $table) {
    array_push($renderers, new Rendering\Renderer($table));
}

foreach ($renderers as $renderer) {

    echo sprintf("<h1 class='toggle'>%s</h1>", $renderer->getTable()->getName());
    echo sprintf("<div class='table %s hidden'>", $renderer->getTable()->getName());

    echo sprintf("<h2>add new item in table %s</h2>", $renderer->getTable()->getName());
    echo $renderer->renderCreateForm('../../api/v2/add_' . $renderer->getTable()->getName(), 'add_new');


    echo sprintf("<h2>Search table %s for unique records</h2>", $renderer->getTable()->getName());
    echo $renderer->renderCreateForm('../../api/v2/search_' . $renderer->getTable()->getName(), 'search');


    echo sprintf("<h2>delete from table %s</h2>", $renderer->getTable()->getName());
    echo $renderer->renderCreateForm('../../api/v2/delete_' . $table->getName(), 'delete');

    echo "</div>";
}
?>


<script src="scripts/admin.js"></script>
</body>
</html>
