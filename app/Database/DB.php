<?php

namespace app\Database;

use app\Services\Session\ErrorSessionType;
use app\Services\Session\SessionManager;
use PDO;

class DB
{
    private static ?PDO $_instance = null;

    private function __construct()
    {
        self::$_instance = new \PDO(
            DB_DRIVER . ":host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8",
            DB_USERNAME,
            DB_PASSWORD
        );
        self::$_instance->setAttribute(
            \PDO::ATTR_ERRMODE,
            \PDO::ERRMODE_EXCEPTION
        );
    }

    private function __clone()
    {
    }

    /**
     * @return PDO|DB|null
     */
    public static function getInstance(): PDO|DB|null
    {
        if (self::$_instance !== null) {
            return self::$_instance;
        }

        return new self();
    }

    /**
     * @return void
     * @throws \Exception
     */
    public static function beginTransaction(): void
    {
        try {
            self::$_instance->beginTransaction();
        } catch (\PDOException $e) {
            throw new \Exception($e->getMessage());
        }
    }

    /**
     * @return void
     * @throws \Exception
     */
    public static function commitTransaction(): void
    {
        try {
            self::$_instance->commit();
        } catch (\PDOException $e) {
            throw new \Exception($e->getMessage());
        }
    }

    /**
     * @return void
     *
     * @throws \Exception
     */
    public static function rollbackTransaction(): void
    {
        try {
            self::$_instance->rollBack();
        } catch (\PDOException $e) {
            throw new \Exception($e->getMessage());
        }
    }

    /**
     * @param string $table
     * @param int $id
     *
     * @return mixed in this case, return associative array or false
     *
     * @throws \Exception
     */
    public static function findById(string $table, int $id): mixed
    {
        $sth = self::$_instance->prepare(
            "SELECT * FROM `{$table}` WHERE `id` = :id"
        );

        $sth->bindValue(":id", $id);

        self::execute($sth);

        return $sth->fetch(\PDO::FETCH_ASSOC);
    }

    /**
     * @param string $table
     * @return int
     * @throws \Exception
     */
    public static function getLastId(string $table = 'student'): int
    {
        $sth = self::$_instance->prepare("SELECT MAX(id) FROM `{$table}`");
        self::execute($sth);
        $id = $sth->fetch(\PDO::FETCH_ASSOC)["MAX(id)"];
        if (empty($id)) {
            return 1;
        }
        return $id + 1;
    }

    /**
     * @throws \Exception
     */
    public static function getAllRows($table): bool|array
    {
        $sth = self::$_instance->prepare("SELECT * FROM :table");
        $sth->bindValue(":table", $table);
        self::execute($sth);
        return $sth->fetchAll();
    }

    /**
     * @throws \Exception
     */
    public static function insertRowFromArray($table, array $data): bool|string
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

    /**
     * @param string $table
     * @param array $data associative array with values to update table
     *
     * @return bool
     *
     * @throws \Exception
     */
    public static function update(string $table, array $data): bool
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

    /**
     * @param string $table
     * @param int $page
     * @param int $limit
     *
     * @return bool|array return false on empty results
     *
     * @throws \Exception
     */
    public static function findLimit(string $table, int $page, int $limit = 50): bool|array
    {
        $limitFrom = ($page - 1) * $limit;
        $sth = self::$_instance->prepare("SELECT * FROM `{$table}` LIMIT {$limitFrom}, {$limit}");
        self::execute($sth);
        return $sth->fetchAll();
    }

    /**
     * @param string $table
     * @param string $data
     *
     * @return bool|array
     *
     * @throws \Exception
     */
    public static function findInAllColumns(string $table, string $data): bool|array
    {
        $sth = self::$_instance->prepare(
            "SELECT * from `{$table}` where concat(name, surname, groupName, email, scoreEge) like :data"
        );
        $sth->bindValue(":data", '%'.$data.'%');
        self::execute($sth);
        return $sth->fetchAll();
    }

    /**
     * @param string $table
     * @param string $firstColumn
     * @param string $secondColumn
     * @param array $data
     *
     * @return mixed
     *
     * @throws \Exception
     */
    public static function findByTwoColumnsAsUnique(
        string $table, string $firstColumn, string $secondColumn, array $data
    ): mixed
    {
        $sth = self::$_instance->prepare(
            "SELECT * FROM {$table} WHERE {$firstColumn} = :first and {$secondColumn} = :second"
        );
        $sth->bindValue(":first", $data[0]);
        $sth->bindValue(":second", $data[1]);
        self::execute($sth);
        return $sth->fetch();
    }

    /**
     * @param \PDOStatement $sth
     * @param array|null $data
     *
     * @return bool
     *
     * @throws \Exception
     */
    private static function execute(\PDOStatement $sth, array $data = null): bool
    {
        try {
            return $sth->execute($data);
        } catch (\PDOException $e) {
            SessionManager::add(new ErrorSessionType(), $e->getMessage());
            throw new \Exception($e->getMessage());
        }
    }
}