<?php

namespace app\Models;

use app\Database\DB;
use app\Entities\Student;
use Exception;

class StudentDataGateway extends DB
{
    public function __construct()
    {
        self::getInstance();
    }

    public function addNewStudent(Student $student, $table = 'student')
    {
        self::beginTransaction();
        $lastId = self::getLastId('student');
        $student->setId($lastId);
        try {
            self::insertRowFromArray('student', $student->getStudentAsArrayIfValid());
            self::commitTransaction();
            return $lastId;
        } catch (\Exception $e) {
            self::rollbackTransaction();
            throw new Exception(
                $e->getMessage()
            );
        }
    }

    public function find($id, $table = 'student')
    {
        return self::findById($table, $id);
    }

    public function updateExistStudent(Student $student, $table = 'student')
    {
        self::beginTransaction();
        try {
            self::updateStudent("student", $student->getStudentAsArrayIfValid());
            self::commitTransaction();
        } catch (Exception $e) {
            self::rollbackTransaction();
            throw new Exception(
                $e->getMessage()
            );
        }
    }

    public function showPaginate($table, $page, $limit = 50)
    {
        return self::findLimit($table, $page, $limit);
    }

    public function searchAllColumns(string $table, string $data)
    {
        return self::findInAllColumns($table, $data);
    }

    public static function getLinksPaginate($table, $page, $limit = 50)
    {
        $maxId = self::getLastId($table);
        $countPages = ceil($maxId / $limit);
        if ($page > $countPages or $page < 1) {
            $page = 1;
        }
        $pages = [];
        if ($page !== 1) {
            $pages['prevFromCurrentPage'] = $page - 1;
        }
        $pages['currentPage'] = $page;
        if ($page < $countPages and $page < $countPages - 1) {
            $pages['nextFromCurrentPage'] = $page + 1;
        }
        if ($page != $countPages) {
            $pages['lastPage'] = $countPages;
        }
        return $pages;
    }

    public function validateAccessToken($token, $id, $table)
    {
        $uniqueTokenWithId = self::findByTwoColumnsAsUnique("student", "id", "accessToken", [$id, $token]);
        return !empty($uniqueTokenWithId);
    }
}