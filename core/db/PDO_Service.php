<?php
class PDO_Service {
    /** @var PDO_Connection */
    private PDO_Connection $connection;
    private string $query;
    private array $params = [];

    public function __construct($query) {
        $this->setQuery($query);
    }

    public function setQuery($query): PDO_Service {
        $this->query = $query;
        return $this;
    }

    public function setConnection($conn) {
        if ($conn instanceof PDO_Connection) {
            $this->connection = $conn;
        } else {
            $this->connection = PDO_Connection::getConnection($conn);
        }
    }

    public function setParam($key, $value) {
        if (is_bool($value)) {
            $currentParam = [$value, PDO::PARAM_BOOL];
        } elseif (is_string($value)) {
            $currentParam = [$value, PDO::PARAM_STR];
        } elseif (is_int($value)) {
            $currentParam = [$value, PDO::PARAM_INT];
        } elseif (is_null($value)) {
            $currentParam = [$value, PDO::PARAM_NULL];
        } elseif ($value instanceof DateTime) {
            $currentParam = [$value->format(DATETIME_DATABASE_FORMAT), PDO::PARAM_STR];
        } else {
            $currentParam = [$value, null];
        }
        $this->params[$key] = $currentParam;
    }

    public function execute() {
        $res = $this->exec();
        return $res->rowCount();
    }

    protected function exec() {
        $res = $this->connection->prepare($this->query);
        foreach ($this->params as $key => $param) {
            $res->bindValue(is_string($key) ? $key : $key + 1, $param[0], $param[1]);
        }
        if (!$res->execute()) {
            doError($res->errorInfo());
        }
        return $res;
    }

    public function fetchAll() {
        $res = $this->exec();
        return $res->fetchAll(PDO::FETCH_OBJ);
    }

    public function fetchRow() {
        $res = $this->exec();
        return $res->fetch(PDO::FETCH_OBJ);
    }

    public function fetchColumn() {
        $res = $this->exec();
        return $res->fetchALL(PDO::FETCH_COLUMN);
    }

    public function fetchCell() {
        $res = $this->exec();
        return $res->fetchColumn();
    }
}
