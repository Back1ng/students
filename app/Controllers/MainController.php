<?php

namespace app\Controllers;

use app\Models\StudentDataGateway;

class MainController
{
    public function index()
    {
        $students = new StudentDataGateway();
        if(! isset($_GET['page'])) {
            $_GET['page'] = 1;
        }
        try {
            $listStudents = $students->showPaginate("student", (int)$_GET['page'], 50);
        } catch (\Exception $e) {
            $_SESSION['ERROR'] = $e->getMessage();
            $listStudents = $students->showPaginate("student", 1, 50);
        }
        $studentGateway = new StudentDataGateway();
        $paginateLinks = $studentGateway->getLinksPaginate('student', (int)$_GET['page']);
        require(__DIR__ . '/../Views/layout.php');
    }
}