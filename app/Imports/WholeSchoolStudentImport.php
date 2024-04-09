<?php

namespace App\Imports;

use App\Models\Section;
use App\Models\Student;
use App\Models\Transport;
use Hamcrest\Arrays\IsArray;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\SkipsErrors;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class WholeSchoolStudentImport implements ToCollection, WithValidation, WithHeadingRow, SkipsOnFailure, SkipsOnError
{

    use Importable, SkipsErrors, SkipsFailures;

    private int $school_id;

    public function __construct(int $school_id)
    {
        $this->school_id = $school_id;

    }

    /**
     * @param Collection $collection
     */
    public function collection(Collection $collection)
    {
        $sections = Section::with('building', 'building.geometryPoint')->where('school_id', $this->school_id)->get();

        foreach ($collection->groupBy('sezione') as $sezioneName => $grouped) {
            if (is_null($sezioneName))
                continue;
            else {
                $section = $sections->first(function ($item) use ($sezioneName) {
                    return Str::contains(Str::lower($item->name), Str::lower($sezioneName));
                });
            }

            if (is_null($section))
                $this->failures()->add("Sezione $sezioneName non trovata. Le righe relative non sono state importate.");

            foreach ($grouped as $row) {

                if (!$row['comune_di_residenza'] || !$row['indirizzo_residenza'])
                    continue;

                $comune_residenza = ucwords(strtolower($row['comune_di_residenza'])) ?? NULL;
                if (!is_null($comune_residenza)) {
                    $residenza_town_istat = getComuneByName($comune_residenza);
                } else {
                    continue;
                }
                $address = '';
                if (!$residenza_town_istat) {
                    $address = $comune_residenza . ' ';
                }


                $student = Student::create([
                    'name' => $row['nome'] ?? NULL,
                    'surname' => $row['cognome'] ?? NULL,
                    'town_istat' => $residenza_town_istat ?? NULL,
                    'section_id' => $section->id,
                    'address' => $address . $row['indirizzo_residenza'] ?? NULL,
                    'town_name' => $comune_residenza
                ]);


                if (isset($row['1_mezzo'])) {
                    $trans_1 = matchTransportNameToId($row['1_mezzo']);

                    if (!is_null($row['1_comune_di_scalo']) && strtolower($row['1_comune_di_scalo']) != 'scuola') {
                        $indirizzo = isset($row['1_comune_indirizzo']) ? getComuneByName($row['1_comune_indirizzo']) : NULL;
                        $comune_scalo = isset($row['1_comune_di_scalo']) ? getComuneByName($row['1_comune_di_scalo']) : NULL;
                    } else {
                        $comune_scalo = $section->building->town_istat;
                        $indirizzo = $section->building->address;
                    }


                    $student->trips()->create([
                        'order' => 1,
                        'transport_1' => $trans_1,
                        'town_istat' => $comune_scalo,
                        'address' => $indirizzo,
                        'is_public_transportation' => in_array($trans_1, Transport::MEZZI_PUBBLICI)
                    ]);
                }

                if (isset($row['2_mezzo'])) {
                    $trans_1 = matchTransportNameToId($row['2_mezzo']);

                    if (!is_null($row['2_comune_di_scalo']) && strtolower($row['2_comune_di_scalo']) != 'scuola') {
                        $indirizzo = isset($row['2_comune_di_scalo']) ? getComuneByName($row['2_comune_di_scalo']) : NULL;
                        $comune_scalo = isset($row['2_comune_di_scalo']) ? getComuneByName($row['2_comune_di_scalo']) : NULL;
                    } else {
                        $comune_scalo = $section->building->town_istat;
                        $indirizzo = $section->building->address;
                    }


                    $student->trips()->create([
                        'order' => 2,
                        'transport_1' => $trans_1,
                        'town_istat' => $comune_scalo,
                        'address' => $indirizzo,
                        'is_public_transportation' => in_array($trans_1, Transport::MEZZI_PUBBLICI)
                    ]);
                }

                if (isset($row['3_mezzo'])) {
                    $trans_1 = matchTransportNameToId($row['3_mezzo']);

                    if (!is_null($row['3_comune_di_scalo']) && strtolower($row['3_comune_di_scalo']) != 'scuola') {
                        $indirizzo = isset($row['3_comune_di_scalo']) ? getComuneByName($row['3_comune_di_scalo']) : NULL;
                        $comune_scalo = isset($row['3_comune_di_scalo']) ? getComuneByName($row['3_comune_di_scalo']) : NULL;
                    } else {
                        $comune_scalo = $section->building->town_istat;
                        $indirizzo = $section->building->address;
                    }

                    $student->trips()->create([
                        'order' => 3,
                        'transport_1' => $trans_1,
                        'town_istat' => $comune_scalo,
                        'address' => $indirizzo,
                        'is_public_transportation' => in_array($trans_1, Transport::MEZZI_PUBBLICI)
                    ]);
                }
            }
        }

    }

    public function rules(): array
    {
        return [
            'comune_di_residenza' => 'required|string'
        ];
    }
}
