<?php

namespace app\Controllers;

use app\Entities\Student;
use app\Models\StudentDataGateway;
use app\Models\StudentValidator;
use app\Services\Cookie\CookieManager;
use app\Services\Session\ErrorSessionType;
use app\Services\Session\SessionManager;
use app\Services\Session\SuccessSessionType;
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
                    $cookieManager = new CookieManager();
                    $cookieManager->set('add', ["", "", "", ""], $this->getCookieTime());
                }
            } catch (Exception $e) {
                $errorInPostQuery = 1;
                SessionManager::add(new ErrorSessionType(), $e->getMessage());
            }
        }
        $isRegistration = 1;
        require(__DIR__ . '/../Views/layout.php');
    }

    public function addNewStudent()
    {
        if (StudentValidator::arrayHaveNeededKeys($_POST)) {
            $student = new Student($_POST);
            $student->setToken($this->getRandomString());
            $isRegistration = 1;
            if (SessionManager::exist(new ErrorSessionType())) {
                $errorInPostQuery = 1;
                header("Location: /regist");
            } else {
                $studentGateway = new StudentDataGateway();
                try {
                    $studentId = $studentGateway->add($student);

                    $cookieManager = new CookieManager();
                    $cookieManager->set(
                        'add',
                        [
                            $studentId,
                            $student->getName(),
                            $student->getSurname(),
                            $student->getToken(),
                        ],
                        $this->getCookieTime()
                    );

                    SessionManager::add(new SuccessSessionType(), "Вы успешно зарегистрировались!");
                    header('Location: /');
                } catch (Exception $e) {
                    $errorInPostQuery = 1;
                    SessionManager::add(new ErrorSessionType(), $e->getMessage());
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
        $cookieManager = new CookieManager();

        if (StudentValidator::arrayHaveNeededKeys($_POST)) {
            $student = new Student($_POST);

            $student->setId   ($cookieManager->get('ID_AUTH_STUDENT'));
            $student->setToken($cookieManager->get('TOKEN'));

            $isRegistration = 1;
            if (SessionManager::exist(new ErrorSessionType())) {
                $errorInPostQuery = 1;
                header("Location: /regist");
            } else {
                $studentGateway = new StudentDataGateway();
                try {
                    $studentId = $studentGateway->updateExistStudent($student);

                    $cookieManager->set(
                        'default',
                        [
                            $student->getId(),
                            $student->getName(),
                            $student->getSurname()
                        ],
                        $this->getCookieTime()
                    );

                    $success = 1;
                    SessionManager::add(new SuccessSessionType(), "Вы успешно обновили свои данные!");
                    header('Location: /');
                } catch (Exception $e) {
                    $errorInPostQuery = 1;
                    SessionManager::add(new ErrorSessionType(), $e->getMessage());
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

    /**
     * @return string
     * @throws Exception
     */
    private function getRandomString(): string
    {
        return bin2hex(random_bytes(16));
    }
}