<?php


namespace app\Controllers;


use app\Database\DB;
use app\Entities\Student;
use app\Models\StudentDataGateway;
use Faker\Factory;
use Faker\Provider\Internet;

class FakerController extends DB
{
    public function __construct()
    {
        self::getInstance();
    }

    public function index()
    {
        $faker = Factory::create();
        $internet = new Internet($faker);
        $studentGateway = new StudentDataGateway();
        for ($i = 0; $i < 100; $i++) {
            $data = [];
            $data['name'] = $faker->firstName();
            $data['surname'] = $faker->lastName();
            $data['sex'] = $faker->numberBetween(0, 1);
            $data['groupName'] = $faker->numberBetween(100, 99999);
            $data['email'] = $internet->email();
            $data['scoreEge'] = $faker->numberBetween(0, 300);
            $data['dateBirth'] = $faker->date();
            $data['citizenship'] = $faker->numberBetween(0, 1);
            $student = new Student($data);
            $studentGateway->addNewStudent($student);
        }
    }
}