<?php

namespace App\Interfaces;

use App\Models\Building;
use App\Models\Student;

interface IQgisService
{

    public static function georefStudent(Student $student);

    public static function georefBuilding(Building $building);

}
