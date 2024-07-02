<?php

namespace App\Services;


use App\Models\Student;
use Barryvdh\DomPDF\Facade\Pdf;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class PedibusService
{

    public function __construct()
    {

    }

    public static function generateQrCodePdf(Student $student)
    {
        $qrCode = QrCode::format('png')->size(600)->generate($student->uuid);
        $data['student'] = $student;
        $data['qrCode'] = $qrCode;
        return Pdf::loadView('pdf.qrcode', $data);
    }



}
