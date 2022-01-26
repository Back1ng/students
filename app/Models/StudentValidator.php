<?php namespace app\Models;

use app\Entities\Student;
use Exception;

class StudentValidator
{
    public static function validateString(string $string, string $field)
    {
        $lengthString = mb_strlen($string);
        if ($lengthString === 0) {
            throw new Exception("Пустая строка " . $field);
        }
        if ($lengthString > 255) {
            throw new Exception("Слишком много символов в поле " . $field);
        }
        if (!is_string($string)) {
            throw new Exception(
                "Неверный тип данных, ожидалась строка, в поле " . $field
            );
        }
        return true;
    }

    public static function validateBoolean(int $bool, string $field)
    {
        if ($bool != 0 and $bool != 1) {
            throw new Exception("Неверное логическое значение");
        }
        return true;
    }

    public static function validateGroup($group, string $field)
    {
        $lengthGroup = mb_strlen($group);
        if ($lengthGroup >= 2 and $lengthGroup <= 5) {
            return true;
        } else {
            throw new Exception("Неверный номер группы в поле " . $field);
        }
    }

    public static function validateEmail($email, string $field)
    {
        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return true;
        } else {
            throw new Exception("Не корректная электронная почта");
        }
    }

    public static function validateScoreEge(int $score, string $field)
    {
        if (!is_int($score)) {
            throw new Exception("Ожидалось числовое значение");
        }
        if ($score < 0) {
            throw new Exception("Ожидалось положительное значение");
        }
        if ($score > 300) {
            throw new Exception(
                "Слишком большое число (максимальное значение - 300)"
            );
        }
        return true;
    }

    public static function validateDateBirth($dateBirth, string $field)
    {
        $explodedDate = explode("-", $dateBirth);

        if (! count($explodedDate) === 3) {
            throw new Exception("Введена неверная дата рождения");
        }

        if (! checkdate($explodedDate[1], $explodedDate[2], $explodedDate[0])) {
            throw new Exception("Введена неверная дата рождения");
        }

        if ($explodedDate[0] >= 1900 and $explodedDate[0] <= date("Y") - 14) {
            return true;
        } else {
            throw new Exception("Введена неверная дата рождения");
        }
    }

    public static function arrayHaveNeededKeys(array $data): bool
    {
        $keys = ['fieldName', 'fieldSurname', 'fieldSex', 'fieldCitizenship', 'fieldGroup', 'fieldScoreEge', 'fieldEmail', 'fieldDateBirth'];
        foreach ($keys as $key) {
            if (false === array_key_exists($key, $data)) {
                return false;
            }
        }
        return true;
    }

    public static function validateStudent(Student $student)
    {
        try {
            self::validateString($student->getName(), '"Имя"');
            self::validateString($student->getSurname(), '"Фамилия"');
            self::validateBoolean($student->getSex(), '"Пол"');
            self::validateGroup($student->getGroup(), '"Группа"');
            self::validateEmail($student->getEmail(), '"Электронная почта"');
            self::validateScoreEge(
                $student->getScoreEge(),
                '"Количество баллов ЕГЭ"'
            );
            self::validateDateBirth(
                $student->getDateBirth(),
                '"Дата рождения"'
            );
            self::validateBoolean($student->getCitizenship(), '"Гражданство"');
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }
}