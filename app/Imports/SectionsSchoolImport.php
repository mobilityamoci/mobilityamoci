<?php

namespace App\Imports;

use App\Models\Building;
use App\Models\Section;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\SkipsErrors;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class SectionsSchoolImport implements ToCollection, WithHeadingRow, SkipsOnFailure, SkipsOnError
{

    use Importable,SkipsErrors, SkipsFailures;
    private int $selectedSchoolId;


    public function __construct(int $selectedSchoolId)
    {
        $this->selectedSchoolId = $selectedSchoolId;
    }


    /**
     * @param Collection $collection
     */
    public function collection(Collection $collection)
    {
        $schoolId = $this->selectedSchoolId;
        foreach ($collection->groupBy('sede') as $buildingName => $grouped) {
            if (is_null($buildingName)) {
                $building = Building::where('school_id', 'ilike', $schoolId)->first();
            } else {
                $building = Building::where('name', 'ilike', $buildingName)->where('school_id')->firstOr(function () {
                    return Building::where('school_id', 'ilike', $this->selectedSchoolId)->first();
                });
            }

            foreach ($grouped as $newSection) {
                $name = $newSection['nome'] ?? null;
                if (is_null($name))
                    continue;
                Section::updateOrCreate([
                    'school_id' => $schoolId,
                    'name' => $name
                ], [
                    'building_id' => $building->id
                ]);
            }
        }
    }
}
