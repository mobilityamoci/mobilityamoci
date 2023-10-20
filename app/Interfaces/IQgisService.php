<?php

namespace App\Interfaces;

use App\Models\Student;

interface IQgisService
{

    public static function georefStudent(Student $student);

}
