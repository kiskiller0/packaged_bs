<?php


$host = "localhost";
$user = "root";
$pass = "";
$db = "learning";
$table = "user";
// DSN: data source name:
$dsn = "mysql:host=" . $host . ";dbname=" . $db;
// create pdo instance:
$pdoConnection = new PDO($dsn, $user, $pass);
//PDO query:
$stmt = $pdoConnection->query('SELECT * from ' . $table . ';');

// set the default fetch mode once and for all:
// $pdoConnection->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ)

while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    var_dump($row);
}


// prepared statements:

// positional parameters
$sql = 'SELECT * FROM ' . $table . ' WHERE  username = ? && password= ?';
// prepare staements:
$stmt = $pdoConnection->prepare($sql);
// exec:
$stmt->execute(['kiskiller0', 'secret ']);

// named parameters
$sql = 'SELECT * FROM ' . $table . ' WHERE username = :username && password = :password';
// prepare staements:
$stmt = $pdoConnection->prepare($sql);
// exec:
$stmt->execute(['username' => 'kiskiller0', 'password' => 'secret']);


// fetch all rows:
$results = $stmt->fetchAll();
// $stmt->fetch to fetch one record.
// var_dump the result object:
var_dump($results);

// get row count:
$stmt->rowCount();

// insert data::

$sql = 'INSERT INTO posts(id, username, passwprd)valise(:value, :value2, :vale3)';
// update
$sql = 'Update posts set body = :body where id = :id';
// delete
$sql = 'Delete from posts where id=:id';

$stmt = $pdoConnection->prepare($sql);

//search
$search = "%post%";
// for limits you need to deactivate emulate mode:


// $pdoConnection->setAttribute(PDO::ATTR_EMULATE_MODE, false)
// now you can pass LIMIT :limit as a query to pdo->prepare


$host = "localhost";
$user = "root";
$pass = "";
$db = "learning";
$table = "user";

// DSN: data source name:


class user
{
    private $dsn;
    private $db = "learning";
    private $host = "localhost";
    private $username = "root";
    private $password = '';
    private $pdo;

    public function __construct()
    {
        $this->dsn = "mysql:host=" . $this->host . ";dbname=" . $this->db;
        $this->pdo = new PDO($this->dsn, $this->username, $this->password);
    }
}

$User = new user();
