<?php

namespace App\Imports;

use App\Models\School;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class SchoolsBuildingsImport implements ToCollection, WithHeadingRow
{
    /**
    * @param Collection $collection
    */
    public function collection(Collection $collection)
    {
        foreach ($collection as $row) {
            $schoolName = $row['scuola'];
            $school = School::where('name', $schoolName)->firstOrCreate([
               'name' => $schoolName,
            ]);

            $sedeName = $row['sede'] ?? 'sede';
            $school->buildings()->create([
                'name' => ucwords($sedeName),
                'address' => $row['indirizzo'] ?? null,
                'town_istat' => getComuneByName($row['comune'] ?? null),
            ]);
        }
    }
}
