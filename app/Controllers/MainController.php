<?php

namespace app\Controllers;

use app\Models\StudentDataGateway;
use app\Services\Paginator;
use app\Services\Session\ErrorSessionType;
use app\Services\Session\SessionManager;

class MainController
{
    public function index()
    {
        $students = new StudentDataGateway();
        $page = $_GET['page'] ?? 1;
        try {
            $listStudents = $students->showPaginate("student", (int)$page, 50);
        } catch (\Exception $e) {
            SessionManager::add(new ErrorSessionType(), $e->getMessage());
            $listStudents = $students->showPaginate("student", 1, 50);
        }
        $paginateLinks = $students->getLinksPaginate(new Paginator(), 'student', (int)$page);
        require(__DIR__ . '/../Views/layout.php');
    }
}