<?php

namespace App\Imports;

use App\Models\Section;
use App\Models\Student;
use App\Models\Transport;
use Illuminate\Support\Facades\Cache;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\OnEachRow;
use Maatwebsite\Excel\Concerns\SkipsErrors;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Row;

class StudentsImport implements OnEachRow, WithHeadingRow, SkipsOnFailure, SkipsOnError
{

    use Importable, SkipsErrors, SkipsFailures;

    private $section_id;

    private $school_address;

    private $school_istat;

    public function __construct($section_id)
    {
        $section = Section::with('building','building.geometryPoint')->find($section_id);
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


            if (isset($row['1_mezzo_opzione_a'])) {
                $trans_1 = Transport::where('name', 'ILIKE',$row['1_mezzo_opzione_a'])->first();

                if (isset($row['1_comune_di_scalo']) && strtolower($row['1_comune_di_scalo']) == strtolower('scuola')) {
                    $comune_scalo = $this->school_istat;
                    $indirizzo = $this->school_address;
                } else {
                    $indirizzo = isset($row['1_comune_indirizzo']) ? getComuneByName($row['1_comune_indirizzo']) : NULL;
                    $comune_scalo = isset($row['1_comune_di_scalo']) ? getComuneByName($row['1_comune_di_scalo']) : NULL;
                }

                $student->trips()->create([
                    'order' => 1,
                    'transport_1' => $trans_1->id,
                    'town_istat' => $comune_scalo,
                    'address' => $indirizzo
                ]);
            }

            if (isset($row['2_mezzo_opzione_a'])) {
                $trans_1 = Transport::where('name', 'ILIKE',$row['2_mezzo_opzione_a'])->first();

                if (isset($row['2_comune_di_scalo']) && strtolower($row['2_comune_di_scalo']) == strtolower('scuola')) {
                    $comune_scalo = $this->school_istat;
                    $indirizzo = $this->school_address;

                } else {
                    $indirizzo = isset($row['2_comune_di_scalo']) ? getComuneByName($row['2_comune_di_scalo']) : NULL;
                    $comune_scalo = isset($row['2_comune_di_scalo']) ? getComuneByName($row['2_comune_di_scalo']) : NULL;
                }


                $student->trips()->create([
                    'order' => 2,
                    'transport_1' => $trans_1->id,
                    'town_istat' => $comune_scalo,
                    'address' => $indirizzo
                ]);
            }

            if (isset($row['3_mezzo_opzione_a'])) {
                $trans_1 = Transport::where('name', 'ILIKE',$row['3_mezzo_opzione_a'])->first();

                if (isset($row['3_comune_di_scalo']) && strtolower($row['3_comune_di_scalo']) == strtolower('scuola')) {
                    $comune_scalo = $this->school_istat;
                    $indirizzo = $this->school_address;
                } else {
                    $indirizzo = isset($row['3_comune_di_scalo']) ? getComuneByName($row['3_comune_di_scalo']) : NULL;
                    $comune_scalo = isset($row['3_comune_di_scalo']) ? getComuneByName($row['3_comune_di_scalo']) : NULL;
                }

                $student->trips()->create([
                    'order' => 3,
                    'transport_1' => $trans_1->id,
                    'town_istat' => $comune_scalo,
                    'address' => $indirizzo
                ]);
            }
        });
    }
}
