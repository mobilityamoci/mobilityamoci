<?php

namespace App\Traits;

trait HasGeometryPoint
{
    public function getGeomAddressAttribute()
    {
        return optional($this->geometryPoint)->point;
    }


}
