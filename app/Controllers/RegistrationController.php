<?php

namespace app\Controllers;

use app\Database\DB;
use app\Entities\Student;
use app\Models\StudentDataGateway;
use app\Models\StudentValidator;
use Exception;

class RegistrationController
{
    public function index()
    {
        if (isset($_COOKIE['ID_AUTH_STUDENT'])) {
            try {
                $studentGateway = new StudentDataGateway();
                $student = new Student($studentGateway->find((int)$_COOKIE['ID_AUTH_STUDENT'], "student"));
            } catch (Exception $e) {
                $errorInPostQuery = 1;
                $_SESSION['ERROR'] = $e->getMessage();
            }
        }
        $isRegistration = 1;
        require(__DIR__ . '/../Views/layout.php');
    }

    public function addNewStudent()
    {
        if (StudentValidator::postHaveNeededKeys()) {
            $student = new Student($_POST);
            $isRegistration = 1;
            if (isset($_SESSION['ERROR'])) {
                $errorInPostQuery = 1;
                header("Location: /regist");
            } else {
                $studentGateway = new StudentDataGateway();
                try {
                    $studentId = $studentGateway->addNewStudent($student);
                    setcookie('ID_AUTH_STUDENT', $studentId, time() + 60 * 60 * 24 * 30 * 12 * 10);
                    setcookie('STUDENT_NAME', $student->getName(), time() + 60 * 60 * 24 * 30 * 12 * 10);
                    setcookie('STUDENT_SURNAME', $student->getSurname(), time() + 60 * 60 * 24 * 30 * 12 * 10);
                    $_SESSION['SUCCESS'] = "Вы успешно зарегистрировались!";
                    header('Location: /');
                } catch (Exception $e) {
                    $errorInPostQuery = 1;
                    $_SESSION['ERROR'] = $e->getMessage();
                    header("Location: ".$_SERVER['HTTP_REFERER']);
                }
            }
            require(__DIR__ . '/../Views/layout.php');
        } else {
            header("Location: /");
        }
    }

    public function updateStudent()
    {
        if (StudentValidator::postHaveNeededKeys()) {
            $student = new Student($_POST);
            $student->setId($_COOKIE['ID_AUTH_STUDENT']);
            $isRegistration = 1;
            if (isset($_SESSION['ERROR'])) {
                $errorInPostQuery = 1;
                header("Location: /regist");
            } else {
                $studentGateway = new StudentDataGateway();
                try {
                    $studentId = $studentGateway->updateExistStudent($student);
                    setcookie('ID_AUTH_STUDENT', $student->getId(), time()+60*60*24*30*12*10);
                    setcookie('STUDENT_NAME', $student->getName(), time()+60*60*24*30*12*10);
                    setcookie('STUDENT_SURNAME', $student->getSurname(), time()+60*60*24*30*12*10);
                    $success = 1;
                    $_SESSION['SUCCESS'] = "Вы успешно обновили свои данные!";
                    header('Location: /');
                } catch (Exception $e) {
                    $errorInPostQuery = 1;
                    $_SESSION['ERROR'] = $e->getMessage();
                    header("Location: ".$_SERVER['HTTP_REFERER']);
                }
            }
            require(__DIR__ . '/../Views/layout.php');
        } else {
            header("Location: /");
        }
    }
}