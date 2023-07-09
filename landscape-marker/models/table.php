<?php

//namespace table;
//use table;

//I should use an enum, and hide it in a namespace to avoid pollution, haven't learned them yet so!
define("EQ", '=');
define("GT", '>');
define("GTE", '>=');
define("LT", '<');
define("LTE", '<=');


enum MODE: string
{
    case EQ = '=';
    case GT = '>';
    case GTE = '>=';
    case LT = '<';
    case LTE = '<=';
}

class Table
{
    protected $name;
    protected $username = 'root';
    protected $password = '1234';
    protected $host = 'mysql';
    protected $db = 'learning';
    protected $pdo;
    protected $needed_fields; // an array containing all the fields of the table; //don't add id and date ... (fields that are auto_inserted by db)
    protected $unique_fields;

    protected $batch = 2; // this is needed for pagination


    public function __construct($name, $needed_fields, $unique_fields)
    {
        $this->name = $name;
        $this->pdo = new PDO(sprintf("mysql:dbname=%s;host=%s", $this->db, $this->host), $this->username, $this->password);
        $this->needed_fields = $needed_fields;
        $this->unique_fields = $unique_fields;
    }

    public function addNew($arr = null)
    {
        if (!$arr || !count($arr)) {
            return ['error' => true, 'msg' => 'do no send me empty arrays!'];
        }

        foreach ($this->needed_fields as $key) {
            // the presence of values should be tested in the api endpoint that receives the post request?
            if (!in_array($key, array_keys($arr)) || trim($arr[$key]) == '') {
                return ['error' => true, 'msg' => sprintf('required field %s is missing!', $key)];
            }
        }

        // checking if its unique fields clash with existing fields
        foreach ($this->unique_fields as $key) {
            if (!in_array($key, array_keys($arr))) {
                continue;
            }
            $s = $this->pdo->prepare(sprintf("SELECT * FROM %s WHERE %s = ?", $this->name, $key));
            $s->execute([$arr[$key]]);
            if ($s->fetch()) {
                return ['error' => true, 'msg' => sprintf("value %s: %s is not unique!", $key, $arr[$key])];
            }
        }


        $sql = sprintf(
            "INSERT INTO %s(%s) VALUES(%s)",
            $this->name,
            implode(',', $this->needed_fields),
            implode(',', str_split(str_repeat('?', count($this->needed_fields)), 1))
        );

        $s = $this->pdo->prepare(
            sprintf(
                "INSERT INTO %s(%s) VALUES(%s)",
                $this->name,
                implode(',', $this->needed_fields),
                implode(',', str_split(str_repeat('?', count($this->needed_fields)), 1))
            )
        );

        $orderedArray = Table::extractArrayFromAssoc($this->needed_fields, $arr);
        if ($s->execute($orderedArray)) {
            return ['error' => false, 'msg' => sprintf("recorde: %s \ncreated successfully", json_encode($arr))];
        } else {
            return ['error' => true, 'msg' => 'something happened, debug your code!'];
        }
    }

    public function getByUniqueValue(string $key, string $value)
    {
        if (!in_array($key, $this->unique_fields)) {
            return ['error' => true, 'msg' => $key . ' is not a unique key in the table, does it even exist?'];
        }
        if ($key == 'iddd')
            return ['error' => true, 'sql' => sprintf("SELECT * FROM %s WHERE %s = ?", $this->name, $key), 'value' => $value];

        // checking if its unique fields clash with existing fields
        $s = $this->pdo->prepare(sprintf("SELECT * FROM %s WHERE %s = ?", $this->name, $key));
        $s->execute([$value]);
        $record = $s->fetch();


        $error = false;

        if (!$record) {
            $error = true;
            $msg = 'record does not exist';
        } else {
            $msg = $record;
        }

        return ['error' => $error, 'msg' => $msg];
    }

    public function getByField($key, $value, $mode): array
    {
        $s = $this->pdo->prepare(sprintf("SELECT * FROM %s WHERE %s %s ?", $this->name, $key, $mode));
        $s->execute([$value]);

        $error = true;
        $msg = 'no records fetched!';
        $data = [];

        if ($fetched = $s->fetchAll()) {
            $error = false;
            $msg = 'success';
            $data = $fetched;
        }

        return ['error' => $error, 'msg' => $data];
    }


    public function getByFieldBatched($key, $value, $mode): array
    {
        $s = $this->pdo->prepare(sprintf("SELECT * FROM %s WHERE %s %s ? ORDER BY %s DESC LIMIT %s", $this->name, $key, $mode, $key, $this->batch));
        $s->execute([$value]);

        $error = true;
        $msg = 'no records fetched!';
        $data = [];

        if ($fetched = $s->fetchAll()) {
            $error = false;
            $msg = 'success';
            $data = $fetched;
        }

        return ['error' => $error, 'msg' => $msg, 'data' => $data];
    }

    public static function extractArrayFromAssoc($needed, $assoc)
    {
        // TODO: remove this and use the built in array_intersect
        $result = [];
        foreach ($needed as $key) {
            array_push($result, $assoc[$key]);
        }

        return $result;
    }

    public function getLastInserted($count = null): array
    {
        if (!$count) {
            $count = $this->batch;
        }

        $s = $this->pdo->prepare(sprintf("SELECT * FROM %s ORDER BY id DESC LIMIT %d", $this->name, $count));
        $s->execute();

        $error = true;
        $msg = 'no records fetched!';
        $data = [];

        if ($count == 1) {
            $fetched = $s->fetch();
        } else {
            $fetched = $s->fetchAll();
        }
        if ($fetched) {
            $error = false;
            $msg = 'success';
            $data = $fetched;
        }

        return ['error' => $error, 'msg' => $fetched];
    }


    public function getUniqueFields(): array
    {
        return $this->unique_fields;
    }

    public function getNeededFields(): array
    {
        return $this->needed_fields;
    }

    public function getName()
    {
        return $this->name;
    }

    //    public function renderRecord(array $data): string
    //    {
    //        $html = '';
    //        $html .= sprintf("<div class='%s'>", $this->name);
    //        foreach (array_keys($this->getNeededFields()) as $field) {
    //            $html .= sprintf("<p class='%s'>%s</p>", $field, $data[$field]);
    //        }
    //        $html .= sprintf("</div>");
    //
    //        return $html;
    //    }
    //
    //    public function renderCreateForm(string $api, string $divClassName = ''): string
    //    {
    //        $html = sprintf("<div class='%s'>", $divClassName);
    //        $html .= sprintf("<form action='%s' method='post'>", $api);
    //
    //        foreach ($this->getNeededFields() as $field) {
    //            $html .= sprintf("<label for='%s'>%s</label>", $field, $field);
    //            $html .= sprintf("<input type='text' name='%s' />", $field);
    //        }
    //
    //        $html .= "<input type='submit' name='submit'/>";
    //        $html .= "</form>";
    //        $html .= "</div>";
    //
    //        return $html;
    //    }
    //
    //
    //    public function renderInquireForm(string $api, string $divClassName = ''): string
    //    {
    //        $html = sprintf("<div class='%s'>", $divClassName);
    //        $html .= sprintf("<form action='%s' method='post'>", $api);
    //
    //        foreach ($this->getUniqueFields() as $field) {
    //            $html .= sprintf("<label for='%s'>%s</label>", $field, $field);
    //            $html .= sprintf("<input type='text' name='%s' />", $field);
    //        }
    //
    //        $html .= "<input type='submit' name='submit'/>";
    //        $html .= "</form>";
    //        $html .= "</div>";
    //
    //        return $html;
    //    }
    // TODO: create a getByFields function that takes an array of arrays: getByFields(['id', 5, GT], ['username', 'kiskiller0', NEQ] ....);
    // and builds a complex query that returns records according to all the passed conditions
}
