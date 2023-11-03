<?php

namespace App\Imports;

use App\Models\Student;
use App\Models\Transport;
use Illuminate\Support\Facades\Cache;
use Maatwebsite\Excel\Concerns\OnEachRow;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Row;

class StudentsImport implements OnEachRow, WithHeadingRow
{
    private $section_id;

    public function __construct($section_id)
    {
        $this->section_id = $section_id;
    }


    public function onRow(array|Row $row)
    {
        if (!isset($row['comune_di_residenza'])) {
            return null;
        }

        \DB::transaction(function () use ($row) {

            $comuni = Cache::get('comuni');

            $comune_residenza = ucwords(strtolower($row['comune_di_residenza'])) ?? NULL;
            if (!is_null($comune_residenza)) {
                $residenza_town_istat = array_search($comune_residenza, $comuni->pluck('comune', 'istat')->toArray());
            }



            $student = Student::create([
                'name' => $row['nome'] ?? NULL,
                'surname' => $row['cognome'] ?? NULL,
                'town_istat' => $residenza_town_istat ?? NULL,
                'section_id' => $this->section_id,
                'address' => $row['indirizzo_residenza'] ?? NULL
            ]);


            if (isset($row['1_mezzo_opzione_a'])) {
                $trans_1 = Transport::where('name', $row['1_mezzo_opzione_a'])->first();
                $trans_2 = isset($row['1_mezzo_opzione_b']) ? Transport::where('name', $row['1_mezzo_opzione_b'])->first() : NULL;
                $comune_scalo = isset($row['1_comune_di_scalo']) ? array_search($row['1_comune_di_scalo'], $comuni->pluck('comune', 'istat')->toArray()) : NULL;
                $student->trips()->create([
                    'order' => 1,
                    'transport_1' => $trans_1->id,
                    'transport_2' => $trans_2->id,
                    'town_istat' => $comune_scalo
                ]);
            }

            if (isset($row['2_mezzo_opzione_a'])) {
                $trans_1 = Transport::where('name', $row['2_mezzo_opzione_a'])->first();
                $trans_2 = isset($row['2_mezzo_opzione_b']) ? Transport::where('name', $row['2_mezzo_opzione_b'])->first() : NULL;
                $comune_scalo = isset($row['2_comune_di_scalo']) ? array_search($row['2_comune_di_scalo'], $comuni->pluck('comune', 'istat')->toArray()) : NULL;
                $student->trips()->create([
                    'order' => 2,
                    'transport_1' => $trans_1->id,
                    'transport_2' => $trans_2->id,
                    'town_istat' => $comune_scalo
                ]);
            }

            if (isset($row['3_mezzo_opzione_a'])) {
                $trans_1 = Transport::where('name', $row['3_mezzo_opzione_a'])->first();
                $trans_2 = isset($row['3_mezzo_opzione_b']) ? Transport::where('name', $row['3_mezzo_opzione_b'])->first() : NULL;
                $comune_scalo = isset($row['3_comune_di_scalo']) ? array_search($row['3_comune_di_scalo'], $comuni->pluck('comune', 'istat')->toArray()) : NULL;
                $student->trips()->create([
                    'order' => 3,
                    'transport_1' => $trans_1->id,
                    'transport_2' => $trans_2->id,
                    'town_istat' => $comune_scalo
                ]);
            }
        });
    }
}
