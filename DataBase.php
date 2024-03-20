<?php

class DB
{
    private $dbhost = "host";
    private $dbport = "5432";
    private $dbuser = "pogres";
    private $dbpasswd = "root";
    private $dbname = "cars";

    private $table;
    private $select = '*';
    private $params = [];

    private $conn;

    public function table($name)
    {
        $this->table = $name;
        return $this;
    }

    public function __construct()
    {
        $this->connect();
    }

    protected function connect()
    {
// Создаем соединение с базой PostgreSQL с указанными выше параметрами
       // $dbconn = pg_connect("host=$host port=5432 dbname=$db user=$username password=$password");
        var_dump ("$this->conn",$this->dbhost, $this->dbuser, $this->dbpasswd, $this->dbname);
        $this->conn = pg_connect("$this->dbhost, $this-> $dbport,$this->dbuser, $this->dbpasswd, $this->dbname");
        if ($this->conn->connect_error) {
            die("Connection failed: " . $this->conn->connect_error);
        }
        echo "Connected successfully - Під’єднано успішно" . '<br>';
        $this->conn->query("SET lc_time_names = 'ua_UA'");
        $this->conn->query("SET NAMES 'utf8'");

        return $this;

    }

    public function where($column, $value)
    {
        $this->params[$column] = $value;

        return $this;
    }

    public function get()
    {
        $response = [];

        $sqlQ = '';
        $s = '';
        foreach ($this->params as $key => $param) {
            $whereOrAnd = array_keys($this->params)[0] === $key ? 'WHERE' : 'AND';
            $sqlQ .= "$whereOrAnd $key =?";
            $s .= 's';
        }

        $sql = $this->conn->prepare('SELECT ' . $this->select . ' FROM ' . $this->table . ' ' . $sqlQ);
        $sql->bind_param($s, implode(',', $this->params));
        $sql->execute();
        $result = $sql->get_result();

        if ($result->num_rows) {
            while ($row = $result->fetch_assoc()) {
                $response[] = $row;
            }
        }

        return $response;
    }

    public function select(...$names)
    {
        $args = func_get_args();

        $this->select = implode(',', $args);

        return $this;
    }

}