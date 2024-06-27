<?php

namespace App\Http\Resources;

use App\Services\QgisService;
use Clickbar\Magellan\Data\Geometries\Point;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StudentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     * @return array
     */
    public function toArray($request)
    {

        $point = $this->pedibusStop->point->point;
        return [
            'id' => $this->uuid,
            'scuola' => $this->school->name,
            'classe' => $this->section->name,
            'fermata' => $this->pedibusStop->fullName(),
            'orario' => $this->pedibusStop->time,
            'percorso_id' => $this->pedibusLine->uuid,
            'fermata_coord' => $point ? QgisService::toWGS84(Point::makeGeodetic($point->getY(), $point->getX()), false) : null,
            'absenceDays' => $this->futureAbsenceDays->map(function ($day) {
                return $day->date->format('Y-m-d');
            }),
        ];
    }
}
