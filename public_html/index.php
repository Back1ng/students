<?php
use app\Controllers\FakerController;
use app\Controllers\MainController;
use app\Controllers\RegistrationController;
use app\Controllers\SearchController;

// for develop, comment to use on prod
//ini_set('error_reporting', E_ALL);
//ini_set('display_errors', 1);
//ini_set('display_startup_errors', 1);

// Load Autoload PSR-4
require '../vendor/autoload.php';

// Load Bootstrap File
require '../app/bootstrap.php';
$route = explode("?", $_SERVER["REQUEST_URI"])[0];

if (isset($_GET['fieldSearch'])) {
    $saved = $_GET['fieldSearch'];
    if ($route !== "/") {
        header("Location: /?fieldSearch={$saved}");
    }
    $controller = new SearchController();
    $controller->index();
} elseif ($route === "/") {
    $controller = new MainController();
    $controller->index();
} elseif ($route === "/regist") {
    $controller = new RegistrationController();
    $controller->index();
} elseif ($route === "/addNewStudent") {
    $controller = new RegistrationController();
    $controller->addNewStudent();
} elseif ($route === "/updateStudent") {
    $controller = new RegistrationController();
    $controller->updateStudent();
} elseif ($route === "/generateFakerData") {
    $controller = new FakerController();
    $controller->index();
} else {
    echo "Данной страницы не существует";
}