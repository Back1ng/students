<?php


namespace app\Controllers;


use app\Database\DB;
use app\Models\StudentValidator;

class SearchController extends DB
{
    public function __construct()
    {
        self::getInstance();
    }

    public function index()
    {
        try {
            if (StudentValidator::validateString(htmlspecialchars($_GET['fieldSearch']), 'Поиск')) {
                $fetchableResult = self::findInAllColumns("student", $_GET['fieldSearch']);
                if (is_array($fetchableResult)) {
                    foreach ($fetchableResult as $key => $value) {
                        $listStudents[] = $value;
                    }
                } else {
                    var_dump($fetchableResult);
                }
            }
            require(__DIR__ . '/../Views/layout.php');
        } catch (\Exception $e) {
            $_SESSION['ERROR'] = "Неверный параметр запроса";
        }
    }
}