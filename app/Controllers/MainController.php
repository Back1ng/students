<?php

namespace app\Controllers;

use app\Models\StudentDataGateway;

class MainController
{
    public function index()
    {
        $students = new StudentDataGateway();
        $page = $_GET['page'] ?? 1;
        try {
            $listStudents = $students->showPaginate("student", (int)$page, 50);
        } catch (\Exception $e) {
            $_SESSION['ERROR'] = $e->getMessage();
            $listStudents = $students->showPaginate("student", 1, 50);
        }
        $paginateLinks = $students->getLinksPaginate('student', (int)$page);
        require(__DIR__ . '/../Views/layout.php');
    }
}