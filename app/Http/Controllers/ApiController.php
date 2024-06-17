<?php

namespace App\Http\Controllers;

use App\Http\Resources\PedibusLineResource;
use App\Http\Resources\PedibusStopResource;
use App\Http\Resources\StudentResource;
use App\Models\PedibusLine;
use App\Models\Student;
use App\Services\QgisService;
use Clickbar\Magellan\Data\Geometries\Point;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;


class  ApiController extends Controller
{

    const PARENT = 'parent';
    const GUARDIAN = 'guardian';
    const TYPE_LOGIN = [self::PARENT, self::GUARDIAN];

    public function authenticate(Request $request)
    {
        $request->validate(
            [
                'uuid' => 'required|uuid',
                'type' => ['required', Rule::in(self::TYPE_LOGIN)]
            ],
            [
                'uuid' => 'Codice QR sbagliato',
                'type' => 'Tipo di utente non valido'
            ]);

        $type = $request->type;
        if ($type == 'parent') {
            $student = Student::where('uuid', $request->uuid)->firstOr(function () {
                return response()->isNotFound();
            });

            $token = $student->createToken($student->uuid, [self::PARENT]);
        } else if ($type == 'guardian') {
            $pedibusLine = PedibusLine::where('uuid', $request->uuid)->firstOr(function () {
                return response()->isNotFound();
            });

            $token = $pedibusLine->createToken($pedibusLine->uuid, [self::GUARDIAN]);
        }

        return response(["data" => ['token' => $token->plainTextToken]], 200);
    }

    public function getParent()
    {
        $student = \Auth::user();

        if (!($student instanceof Student)) {
            return response()->isNotFound();
        }

        return new StudentResource($student);
    }

    public function postAbsenceDays(Request $request)
    {

        $student = \Auth::user();

        if (!($student instanceof Student)) {
            return response()->isNotFound();
        }

        if (!$request->get('days')) {
            return response()->json(['message' => 'Giorni non validi'], 400);
        }

        $student->absenceDays()->delete();
        $student->absenceDays()->createMany(collect($request->get('days'))->map(function ($day) {
            return ['date' => $day];
        }));

        return response(['message' => 'Giorni di assenza aggiornati'], 200);
    }

    public function getPedibusLine(PedibusLine $pedibusLine)
    {
        return new PedibusLineResource($pedibusLine);
    }

    public function getPedibusStops(PedibusLine $pedibusLine)
    {

        return PedibusStopResource::collection($pedibusLine->stops);
    }

}
