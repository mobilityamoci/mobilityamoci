<?php

namespace App\Http\Resources;

use App\Services\QgisService;
use Clickbar\Magellan\Data\Geometries\Point;
use Illuminate\Http\Resources\Json\JsonResource;

class PedibusStopResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $point = optional($this->point)->point;

        return [
            'id' => $this->uuid,
            'name' => $this->name,
            'address' => $this->address,
            'order' => $this->order,
            'coordinates' => $point ? QgisService::toWGS84(Point::makeGeodetic($point->getY(), $point->getX()), false) : null
        ];
    }
}
