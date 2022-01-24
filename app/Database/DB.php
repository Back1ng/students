<?php namespace app\Database;

use PDO;

class DB
{
    private static $_instance = null;

    private function __construct()
    {
        self::$_instance = new \PDO(
            DB_DRIVER . ":host=" . DB_HOST . ";dbname=" . DB_NAME
            . ";charset=utf8",
            DB_USERNAME,
            DB_PASSWORD
        );
        self::$_instance->setAttribute(
            PDO::ATTR_ERRMODE,
            PDO::ERRMODE_EXCEPTION
        );
    }

    private function __clone()
    {
    }

    public function __wakeup()
    {
    }

    public static function getInstance()
    {
        if (self::$_instance !== null) {
            return self::$_instance;
        }
        return new self();
    }

    public static function beginTransaction()
    {
        try {
            self::$_instance->beginTransaction();
        } catch (\PDOException $e) {
            throw new \RuntimeException(
                $e->errorInfo[2],
                $e->errorInfo[1],
                $e
            );
        }
    }

    public static function commitTransaction()
    {
        try {
            self::$_instance->commit();
        } catch (\PDOException $e) {
            throw new \RuntimeException(
                $e->errorInfo[2],
                $e->errorInfo[1],
                $e
            );
        }
    }

    public static function rollbackTransaction()
    {
        try {
            self::$_instance->rollBack();
        } catch (\PDOException $e) {
            throw new \RuntimeException(
                $e->errorInfo[2],
                $e->errorInfo[1],
                $e
            );
        }
    }

    public static function findById($table, $id)
    {
        $sth = self::$_instance->prepare(
            "SELECT * FROM `{$table}` WHERE `id` = :id"
        );

        $sth->bindValue(":id", $id);

        self::execute($sth);

        return $sth->fetch(\PDO::FETCH_ASSOC);
    }

    public static function getLastId($table = 'student')
    {
        $sth = self::$_instance->prepare("SELECT MAX(id) FROM `{$table}`");
        self::execute($sth);
        $id = $sth->fetch(\PDO::FETCH_ASSOC)["MAX(id)"];
        if (empty($id)) {
            return 1;
        }
        return $id + 1;
    }

    public static function getAllRows($table)
    {
        $sth = self::$_instance->prepare("SELECT * FROM :table");
        $sth->bindValue(":table", $table);
        self::execute($sth);
        return $sth->fetchAll();
    }

    public static function insertRowFromArray($table, array $data)
    {
        $values = "";
        foreach ($data as $key => $value) {
            $values === "" ? $values .= ":" . $key : $values .= ", :" . $key;
        }
        $sth = self::$_instance->prepare(
            "INSERT INTO `{$table}` VALUES ({$values})"
        );
        foreach ($data as $key => $value) {
            $sth->bindValue(':' . $key, $value);
        }
        self::execute($sth);
        return self::$_instance->lastInsertId();
    }

    public static function updateStudent($table, array $data)
    {
        $values = "";
        foreach ($data as $key => $value) {
            if ($key === 'id') continue;
            $values === "" ? $values .= $key . "=:" . $key : $values .= ", " . $key . "=:" . $key;
        }
        $sth = self::$_instance->prepare(
            "UPDATE `{$table}` SET {$values} WHERE id=:id"
        );
        return self::execute($sth, $data);
    }

    public static function findLimit($table, $page, $limit = 50)
    {
        $limitFrom = ($page - 1) * $limit;
        $sth = self::$_instance->prepare("SELECT * FROM `{$table}` LIMIT {$limitFrom}, {$limit}");
        self::execute($sth);
        return $sth->fetchAll();
    }

    public static function findInAllColumns(string $table, string $data) {
        $sth = self::$_instance->prepare(
            "SELECT * from `{$table}` where concat(name, surname, groupName, email, scoreEge) like :data"
        );
        $sth->bindValue(":data", '%'.$data.'%');
        self::execute($sth);
        return $sth->fetchAll();
    }

    public static function findByTwoColumnsAsUnique(string $table, string $firstColumn, string $secondColumn, array $data)
    {
        $sth = self::$_instance->prepare("SELECT * FROM {$table} WHERE {$firstColumn} = :first and {$secondColumn} = :second");
        $sth->bindValue(":first", $data[0]);
        $sth->bindValue(":second", $data[1]);
        self::execute($sth);
        return $sth->fetch();
    }

    private static function execute(\PDOStatement $sth, $data = null)
    {
        try {
            return $sth->execute($data);
        } catch (\PDOException $e) {
            $_SESSION['ERROR'] = $e->errorInfo;
            throw new \Exception($e->getMessage());
        }
    }
}