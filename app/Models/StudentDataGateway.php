<?php

namespace app\Models;

use app\Database\DB;
use app\Entities\Student;
use app\Services\PaginatorInterface;
use app\Services\Paginator;
use Exception;

class StudentDataGateway extends DB
{
    public function __construct()
    {
        self::getInstance();
    }

    /**
     * @throws Exception
     */
    public function add(Student $student, string $table = 'student')
    {
        self::beginTransaction();
        $lastId = self::getLastId($table);
        $student->setId($lastId);
        try {
            self::insertRowFromArray($table, $student->getStudentAsArray());
            self::commitTransaction();
            return $lastId;
        } catch (\Exception $e) {
            self::rollbackTransaction();
            throw new Exception(
                $e->getMessage()
            );
        }
    }

    /**
     * @throws Exception
     */
    public function find(int $id, string $table = 'student')
    {
        return self::findById($table, $id);
    }

    /**
     * @param Student $student
     * @param string $table
     *
     * @return void
     *
     * @throws Exception
     */
    final public function updateExistStudent(
        Student $student,
        string $table = 'student'
    ): void {
        self::beginTransaction();
        try {
            self::update($table, $student->getStudentAsArray());
            self::commitTransaction();
        } catch (Exception $e) {
            self::rollbackTransaction();
            throw new Exception(
                $e->getMessage()
            );
        }
    }

    /**
     * @param string $table
     * @param int $page
     * @param int $limit
     *
     * @return bool|array
     *
     * @throws Exception
     */
    final public function showPaginate(string $table, int $page, int $limit = 50): bool|array
    {
        return self::findLimit($table, $page, $limit);
    }

    /**
     * @param string $table
     * @param string $data
     *
     * @return bool|array
     *
     * @throws Exception
     */
    final public function searchAllColumns(string $table, string $data): bool|array
    {
        return self::findInAllColumns($table, $data);
    }

    /**
     * @param PaginatorInterface $paginator
     * @param string $table
     * @param int $page
     * @param int $limit
     * @return array
     *
     * @throws Exception
     */
    public static function getLinksPaginate(PaginatorInterface $paginator, string $table, int $page, int $limit = 50): array
    {
        $countRows = self::getLastId($table);

        return $paginator->getPages($page, $countRows, $limit);
    }

    /**
     * @param string $token
     * @param int $id
     * @param string $table
     *
     * @return bool
     *
     * @throws Exception
     */
    final public function validateAccessToken(string $token, int $id, string $table = 'student'): bool
    {
        $uniqueTokenWithId = self::findByTwoColumnsAsUnique(
            $table, "id", "accessToken",
            [
                $id, $token
            ]
        );
        return !empty($uniqueTokenWithId);
    }
}