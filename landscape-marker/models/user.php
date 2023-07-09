<?php

require_once $_SERVER['DOCUMENT_ROOT'] . "/models/table.php";

class user extends Table
{
    private $minUsernameLength = 4;
    private $minPasswordLength = 4;

    public function __construct($name, $needed_fields, $unique_fields)
    {
        parent::__construct($name, $needed_fields, $unique_fields);
    }

    public function getUsernameLike($username)
    {
        $s = $this->pdo->prepare("SELECT * FROM user WHERE username LIKE ?");
        $s->execute(["%$username%"]);
        return $s->fetchAll();
    }

    #TODO: profile pic is just a boolean: true or false, if true fetch /public/profiles/username.png else fetch default.png
}

$User = new user("user", ['username', 'email', 'password', 'picture'], ['id', 'username', 'email']);
