<?php

require_once $_SERVER['DOCUMENT_ROOT'] . "/models/table.php";

class Post extends Table
{
    public function __construct($name, $needed_fields, $unique_fields)
    {
        parent::__construct($name, $needed_fields, $unique_fields);
    }

    public function getTitleLike($title)
    {
        $s = $this->pdo->prepare("SELECT * FROM post WHERE content LIKE ?");
        $s->execute(["%$title%"]);
        return $s->fetchAll();
    }
}

$post = new Post("post", ['content', 'userid'], ['id']);
