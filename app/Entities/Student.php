<?php namespace app\Entities;

use app\Models\StudentValidator;
use app\Services\Session\ErrorSessionType;
use app\Services\Session\SessionManager;

class Student
{
    private $id;
    private $name;
    private $surname;
    private $sex;
    private $group;
    private $email;
    private $scoreEge;
    private $dateBirth;
    private $citizenship;
    private $token;

    public function __construct($data)
    {
        if ($_POST === $data and StudentValidator::arrayHaveNeededKeys($data)) {
            $this->name = $data['fieldName'];
            $this->surname = $data['fieldSurname'];
            $this->sex = $data['fieldSex'];
            $this->group = $data['fieldGroup'];
            $this->email = $data['fieldEmail'];
            $this->scoreEge = $data['fieldScoreEge'];
            $this->dateBirth = $data['fieldDateBirth'];
            $this->citizenship = $data['fieldCitizenship'];
            $result = StudentValidator::validateStudent($this);
            if ($result !== null) {
                SessionManager::add(new ErrorSessionType(), $result);
            }
        } else {
            $this->name = $data['name'];
            $this->surname = $data['surname'];
            $this->sex = $data['sex'];
            $this->group = $data['groupName'];
            $this->email = $data['email'];
            $this->scoreEge = $data['scoreEge'];
            $this->dateBirth = $data['dateBirth'];
            $this->citizenship = $data['citizenship'];
            $this->token = $data['accessToken'];
        }
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    public function setId(int $id)
    {
        $this->id = $id;
    }

    public function getStudentAsArray(): bool|array
    {
        try {
            StudentValidator::validateStudent($this);
            return [
                'id' => $this->getId(),
                'name' => $this->getName(),
                'surname' => $this->getSurname(),
                'sex' => $this->getSex(),
                'groupName' => $this->getGroup(),
                'email' => $this->getEmail(),
                'scoreEge' => $this->getScoreEge(),
                'dateBirth' => $this->getDateBirth(),
                'citizenship' => $this->getCitizenship(),
                'accessToken' => $this->getToken()
            ];
        } catch (\Exception $e) {
            return false;
        }
    }

    public function getName()
    {
        return $this->name;
    }

    public function getSurname()
    {
        return $this->surname;
    }

    public function getSex()
    {
        return $this->sex;
    }

    public function getGroup()
    {
        return $this->group;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function getScoreEge()
    {
        return $this->scoreEge;
    }

    public function getDateBirth()
    {
        return $this->dateBirth;
    }

    public function getCitizenship()
    {
        return $this->citizenship;
    }

    public function setToken(string $token)
    {
        $this->token = $token;
    }

    public function getToken()
    {
        return $this->token;
    }
}