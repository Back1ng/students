<?php


namespace app\Controllers;


use app\Models\StudentDataGateway;
use app\Models\StudentValidator;
use app\Services\Session\ErrorSessionType;
use app\Services\Session\SessionManager;

class SearchController
{
    public function index()
    {
        $studentGateway = new StudentDataGateway();
        try {
            if (StudentValidator::validateString(htmlspecialchars($_GET['fieldSearch']), 'Поиск')) {
                $fetchableResult = $studentGateway->searchAllColumns("student", $_GET['fieldSearch']);
                if (is_array($fetchableResult)) {
                    foreach ($fetchableResult as $student) {
                        $listStudents[] = $student;
                    }
                }
            }
            require(__DIR__ . '/../Views/layout.php');
        } catch (\Exception $e) {
            SessionManager::add(new ErrorSessionType(), "Неверный параметр запроса");
        }
    }
}