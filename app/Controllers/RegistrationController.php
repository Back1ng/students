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
                if ($studentGateway->validateAccessToken($_COOKIE['TOKEN'] ?: 0, $_COOKIE['ID_AUTH_STUDENT'], "student")) {
                    $student = new Student($studentGateway->find((int)$_COOKIE['ID_AUTH_STUDENT'], "student"));
                } else {
                    setcookie('ID_AUTH_STUDENT', "", time()-3600);
                    setcookie('STUDENT_NAME'   , "", time()-3600);
                    setcookie('STUDENT_SURNAME', "", time()-3600);
                }
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
            $student->setToken(bin2hex(random_bytes(16)));
            $isRegistration = 1;
            if (isset($_SESSION['ERROR'])) {
                $errorInPostQuery = 1;
                header("Location: /regist");
            } else {
                $studentGateway = new StudentDataGateway();
                try {
                    $studentId = $studentGateway->addNewStudent($student);
                    setcookie('ID_AUTH_STUDENT', $studentId,             $this->getCookieTime());
                    setcookie('STUDENT_NAME',    $student->getName(),    $this->getCookieTime());
                    setcookie('STUDENT_SURNAME', $student->getSurname(), $this->getCookieTime());
                    setcookie('TOKEN',           $student->getToken(),   $this->getCookieTime());
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

            $student->setId   ($_COOKIE['ID_AUTH_STUDENT']);
            $student->setToken($_COOKIE['TOKEN']);

            $isRegistration = 1;
            if (isset($_SESSION['ERROR'])) {
                $errorInPostQuery = 1;
                header("Location: /regist");
            } else {
                $studentGateway = new StudentDataGateway();
                try {
                    $studentId = $studentGateway->updateExistStudent($student);
                    setcookie('ID_AUTH_STUDENT', $student->getId(),      $this->getCookieTime());
                    setcookie('STUDENT_NAME',    $student->getName(),    $this->getCookieTime());
                    setcookie('STUDENT_SURNAME', $student->getSurname(), $this->getCookieTime());
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

    private function getCookieTime()
    {
        return time() + 60 * 60 * 24 * 30 * 12 * 10;
    }
}