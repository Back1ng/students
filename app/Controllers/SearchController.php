<?php


namespace app\Controllers;


use app\Models\StudentDataGateway;
use app\Models\StudentValidator;

class SearchController
{
    public function index()
    {
        $studentGateway = new StudentDataGateway();
        try {
            if (StudentValidator::validateString(htmlspecialchars($_GET['fieldSearch']), 'Поиск')) {
                $fetchableResult = $studentGateway->searchAllColumns("student", $_GET['fieldSearch']);
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