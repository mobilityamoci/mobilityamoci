<?php

namespace App\Http\Controllers;

use App\Models\Building;
use App\Models\Student;
use App\Services\PedibusService;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public static function deleteAllStudents(Building $building)
    {
        $building->sections()->each(function ($section) {
            $section->students()->each(function ($student) {
                $student->delete();
            });
        });
    }

    public static function testQrCode()
    {
        $student = Student::find(3872);
        return PedibusService::generateQrCodePdf($student)->stream();
    }
}
