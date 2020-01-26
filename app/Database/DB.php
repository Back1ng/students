<?php namespace app\Database;

use Exception;
use PDO;

class DB
{
    private static $_instance = null;

    private function __construct()
    {
        self::$_instance = new PDO(
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

    private function __wakeup()
    {
    }

    public static function getInstance()
    {
        if (self::$_instance !== null) {
            return self::$_instance;
        }
        return new self();
    }

    public static function startTransaction()
    {
        self::$_instance->beginTransaction();
    }

    public static function commitTransaction()
    {
        self::$_instance->commit();
    }

    public static function rollbackTransaction()
    {
        self::$_instance->rollBack();
    }

    public static function findById($table, $id)
    {
        $sth = self::$_instance->prepare(
            "SELECT * FROM `{$table}` WHERE `id` = :id"
        );
        $sth->bindValue(":id", $id);
        $sth->execute();
        return $sth->fetch(\PDO::FETCH_ASSOC);
    }

    public static function getLastId($table = 'student')
    {
        $sth = self::$_instance->prepare("SELECT MAX(id) FROM `{$table}`");
        $sth->execute();
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
        $sth->execute();
        return $sth->fetchAll();
    }

    public static function insertRowFromArray($table, array $data)
    {
        $values = "";
        foreach ($data as $key => $value) {
            $values === "" ? $values .= ":" . $key : $values .= ", :" . $key;
        }
        self::$_instance->setAttribute(
            \PDO::ATTR_ERRMODE,
            \PDO::ERRMODE_EXCEPTION
        );
        $sth = self::$_instance->prepare(
            "INSERT INTO `{$table}` VALUES ({$values})"
        );
        foreach ($data as $key => $value) {
            $sth->bindValue(':' . $key, $value);
        }
        $sth->execute();
        return self::$_instance->lastInsertId();
    }

    public static function updateStudent($table, array $data)
    {
        $sth = self::$_instance->prepare(
            "UPDATE `{$table}` SET name=:name, surname=:surname, sex=:sex, groupName=:group, email=:email, scoreEge=:scoreEge, dateBirth=:dateBirth, citizenship=:citizenship WHERE id=:id"
        );
        $stmt = $sth->execute($data);
        return true;
    }

    public static function findLimit($table, $page, $limit = 50)
    {
        try {
            $limitFrom = ($page - 1) * $limit;
            $sth = self::$_instance->prepare("SELECT * FROM `{$table}` LIMIT {$limitFrom}, {$limit}");
            $sth->execute();
            return $sth->fetchAll();
        } catch (Exception $e) {
            throw new Exception('Произошла непредвиденная ошибка');
        }
    }

    public static function findInAllColumns(string $table, string $data) {
        try {
            $sth = self::$_instance->prepare(
                "SELECT * from `{$table}` where concat(name, surname, groupName, email, scoreEge) like :data"
            );
            $sth->bindValue(":data", '%'.$data.'%');
            $sth->execute();
            return $sth->fetchAll();
        } catch (Exception $e) {
            $_SESSION['ERROR'] = $e->getMessage();
            return [];
        }
    }
}