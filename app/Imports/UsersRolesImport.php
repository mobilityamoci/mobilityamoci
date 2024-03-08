<?php

namespace App\Imports;

use App\Models\School;
use App\Models\User;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\SkipsErrors;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithCalculatedFormulas;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class UsersRolesImport implements ToCollection, WithCalculatedFormulas, WithHeadingRow, SkipsOnFailure, SkipsOnError
{
    use Importable, SkipsErrors, SkipsFailures;

    /**
     * @param Collection $collection
     */
    public function collection(Collection $collection)
    {
        $now = now();
        foreach ($collection->groupBy('scuola') as $index => $grouped) {
            if ($index)
                $scuola = School::where('name', 'ilike', "%$index%")->first();
            foreach ($grouped as $row) {
                if (!$row['nome'] || !$row['cognome'])
                    continue;
                $user = User::create([
                    'name' => $row['nome'],
                    'surname' => $row['cognome'],
                    'email' => $row['email'],
                    'password' => bcrypt($row['password']),
                    'accepted_at' => $now
                ]);

                $user->assignRole($row['ruolo']);
                if ($index)
                    $user->schools()->attach($scuola->id);
            }
        }
    }
}
