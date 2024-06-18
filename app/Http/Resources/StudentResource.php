<?php

namespace App\Http\Resources;

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
        return [
            'id' => $this->uuid,
            'scuola' => $this->school->name,
            'classe' => $this->section->name,
            'fermata' => $this->pedibusStop->fullName(),
            'orario' => $this->pedibusStop->time,
            'percorso_id' => $this->pedibusLine->uuid,
            'absenceDays' => $this->futureAbsenceDays->map(function ($day) {
                return $day->date->format('Y-m-d');
            }),
        ];
    }
}
