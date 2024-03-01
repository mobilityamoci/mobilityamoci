<?php

namespace App\Imports;

use App\Models\School;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\SkipsErrors;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class SchoolsBuildingsImport implements ToCollection, WithHeadingRow, SkipsOnFailure, SkipsOnError
{
    use Importable, Importable, SkipsErrors, SkipsFailures;

    /**
    * @param Collection $collection
    */
    public function collection(Collection $collection)
    {
        foreach ($collection as $row) {
            $schoolName = $row['scuola'];
            if (is_null($schoolName))
                continue;
            $school = School::where('name', 'ilike',$schoolName)->firstOrCreate([
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
