<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <link href="css/bootstrap.min.css" rel="stylesheet" type="text/css">
    <title>Студенты</title>
</head>
<body>
<?php require(__DIR__ . '/header.php'); ?>
<div class="container">
    <?php
    if($_SERVER['REQUEST_METHOD'] === "GET") {
        if (isset($_SESSION['SUCCESS'])) {
            require(__DIR__ . '/successAlert.php');
            $_SESSION['SUCCESS'] = null;
        }
        if (isset($_SESSION['ERROR'])) {
            require(__DIR__ . '/errorAlert.php');
            $_SESSION['ERROR'] = null;
        }
    }
    if (isset($listStudents)) {
        require(__DIR__ . '/students.php');
    }
    if (isset($paginateLinks)) {
        require(__DIR__ . '/paginateLinks.php');
    }
    if (isset($isRegistration)) {
        require(__DIR__ . '/registrationForm.php');
    }
    ?>
</div>
</body>
</html>