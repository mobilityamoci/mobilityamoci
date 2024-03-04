<?php

namespace App\Imports;

use App\Models\Section;
use App\Models\Student;
use App\Models\Transport;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\OnEachRow;
use Maatwebsite\Excel\Concerns\SkipsErrors;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Row;


//      ATTUALMENTE NON IN USO, NON AGGIORNATO. VERSIONE PIù GENERICA: WHOLESCHOOLSSTUDENTSIMPORT
class StudentsImport implements OnEachRow, WithHeadingRow, SkipsOnFailure, SkipsOnError
{

    use Importable, SkipsErrors, SkipsFailures;

    private $section_id;

    private $school_address;

    private $school_istat;

    public function __construct($section_id)
    {
        $section = Section::with('building', 'building.geometryPoint')->find($section_id);
        $this->section_id = $section_id;
        $this->school_address = $section->building->geometryPoint->address_request;
        $this->school_istat = $section->building->town_istat;
    }


    public function onRow(array|Row $row)
    {
        if (!isset($row['comune_di_residenza'])) {
            return null;
        }

        \DB::transaction(function () use ($row) {

            $comuni = getComuniArray();

            $comune_residenza = ucwords(strtolower($row['comune_di_residenza'])) ?? NULL;
            if (!is_null($comune_residenza)) {
                $residenza_town_istat = getComuneByName($comune_residenza);
            }
            $address = '';
            if (is_null($residenza_town_istat)) {
                $residenza_town_istat = config('custom.geo.piacenza_istat');
                $address = $comune_residenza . ' ';
            }


            $student = Student::create([
                'name' => $row['nome'] ?? NULL,
                'surname' => $row['cognome'] ?? NULL,
                'town_istat' => $residenza_town_istat ?? NULL,
                'section_id' => $this->section_id,
                'address' => $address . $row['indirizzo_residenza'] ?? NULL
            ]);



        });
    }
}
