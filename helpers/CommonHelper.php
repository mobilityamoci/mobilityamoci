<?php


use App\Enums\RolesEnum;
use App\Models\Comune;
use App\Models\School;
use App\Models\Transport;
use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;


function getCanSeeNameRoles(): array
{
    return [RolesEnum::INSEGNANTE->value, RolesEnum::MM_SCOLASTICO->value];
}

function canSeeName(User $user): bool
{
    return $user->hasAnyRole(getCanSeeNameRoles());
}

function getSchoolsFromUser(User $user): Collection
{
    if ($user->hasPermissionTo('all_schools')) {
        return School::all()->pluck('id');
    } else {
        return $user->schools()->pluck('id');
    }
}

function getComune($town_istat)
{
    return optional(Comune::where('cod_istat', $town_istat)->first())->label ?? '';
}


function getComuniArray()
{
    return Comune::all()->pluck(['label'], 'cod_istat');
}

function getComuneByName($name)
{
    $comuni = Comune::all();
    $residenza_town_istat = array_search(strtoupper($name), $comuni->pluck('label', 'cod_istat')->toArray());

    if ($residenza_town_istat)
        return $residenza_town_istat;

    $residenza_town_istat = array_search(soundex($name), $comuni->pluck('labelSoundex', 'cod_istat')->toArray());

    if ($residenza_town_istat)
        return $residenza_town_istat;

    return null;

}

function sanitizeAddress(string $string)
{
    $string = preg_replace('/\w*\.\w*/i', '', $string);
    return preg_replace('/\b(?!(via)\b) [a-zA-Z]{1,3} \b/i', ' ', $string);
}

function matchTransportNameToId(string $name)
{
    return match (true) {
        Str::contains(Str::lower($name), ['bus', 'bus interno', 'bus comunale', 'autobus', 'pullman', 'pulman', 'autobus comunale']) => Transport::BUS_COMUNALE,
        Str::contains(Str::lower($name), ['in macchina', 'macchina', 'auto', 'automobile', 'moto', 'motocicletta', 'motorino', 'scooter']) => Transport::AUTO,
        Str::contains(Str::lower($name), 'auto collettiva (2 studenti)') => Transport::AUTO_2,
        Str::contains(Str::lower($name), 'auto collettiva (3+ studenti)') => Transport::AUTO_3,
        Str::contains(Str::lower($name), ['piedi', 'a piedi']) => Transport::PIEDI,
        Str::contains(Str::lower($name), ['bici', 'bicicletta']) => Transport::BICICLETTA,
        Str::contains(Str::lower($name), 'treno') => Transport::TRENO,
        default => Transport::AUTO //OPINABILE
    };

}

function getUserSchools($onlyPopulated = false, $with = [], $withCount = []): Collection
{
    $user = Auth::user();
    if ($user->can('all_schools')) {
        $q = School::with($with)->withCount($withCount);
    } else {
        $q = $user->schools()->with($with)->withCount($withCount);
    }
    if ($onlyPopulated)
        $q->whereHas('sections');
    return $q->get();
}

function getUserStudents($onlyPopulated = false)
{
    $schools = getUserSchools(true, ['students']);
    $students = collect();
    foreach ($schools as $school) {
        $students->push($school->students);
    }

    return $students->flatten();
}

function getUserSections($onlyPopulated = false)
{
    $schools = getUserSchools(true, ['sections']);
    $sections = collect();
    foreach ($schools as $school) {
        $sections->push($school->sections);
    }

    return $sections->flatten();
}

function sanitize($string, $service = false)
{
    if (!$service) {
        $string = str_replace(' ', '_', $string); // Replaces all spaces with hyphens.
    }

    $unwanted_array = ['Š' => 'S', 'š' => 's', 'Ž' => 'Z', 'ž' => 'z', 'À' => 'A', 'Á' => 'A', 'Â' => 'A', 'Ã' => 'A', 'Ä' => 'A', 'Å' => 'A', 'Æ' => 'A', 'Ç' => 'C', 'È' => 'E', 'É' => 'E',
        'Ê' => 'E', 'Ë' => 'E', 'Ì' => 'I', 'Í' => 'I', 'Î' => 'I', 'Ï' => 'I', 'Ñ' => 'N', 'Ò' => 'O', 'Ó' => 'O', 'Ô' => 'O', 'Õ' => 'O', 'Ö' => 'O', 'Ø' => 'O', 'Ù' => 'U',
        'Ú' => 'U', 'Û' => 'U', 'Ü' => 'U', 'Ý' => 'Y', 'Þ' => 'B', 'ß' => 'Ss', 'à' => 'a', 'á' => 'a', 'â' => 'a', 'ã' => 'a', 'ä' => 'a', 'å' => 'a', 'æ' => 'a', 'ç' => 'c',
        'è' => 'e', 'é' => 'e', 'ê' => 'e', 'ë' => 'e', 'ì' => 'i', 'í' => 'i', 'î' => 'i', 'ï' => 'i', 'ð' => 'o', 'ñ' => 'n', 'ò' => 'o', 'ó' => 'o', 'ô' => 'o', 'õ' => 'o',
        'ö' => 'o', 'ø' => 'o', 'ù' => 'u', 'ú' => 'u', 'û' => 'u', 'ý' => 'y', 'þ' => 'b', 'ÿ' => 'y', "'" => ''];

    $string = strtr($string, $unwanted_array);

    return preg_replace('/\W/', '', $string); // Removes special chars.
}
