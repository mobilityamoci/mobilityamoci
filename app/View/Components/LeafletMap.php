<?php

namespace App\View\Components;

use Clickbar\Magellan\Data\Geometries\Point;
use Illuminate\Support\Str;
use Illuminate\View\Component;
use Illuminate\View\View;

class LeafletMap extends Component
{

    const DEFAULTMAPID = "defaultMapId";

    public int $zoomLevel;

    public int $maxZoomLevel;

    public array $centerPoint;

    public array $markers;

    public array $polyLines;

    public $tileHost;

    public $mapId;

    public string $attribution;

    public string $leafletVersion;

    public function __construct(
        $centerPoint = [0, 0],
        $markers = [],
        $polyLines = [],
        $zoomLevel = 13,
        $maxZoomLevel = 18,
        $tileHost = 'openstreetmap',
        $id = self::DEFAULTMAPID,
        $attribution = 'Map data &copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap contributors, Imagery © Mapbox.com',
        $leafletVersion = "latest",
    )
    {
        $this->polyLines = $polyLines;
        $this->centerPoint = $centerPoint;
        $this->zoomLevel = $zoomLevel;
        $this->maxZoomLevel = $maxZoomLevel;
        $this->markers = $markers;
        $this->tileHost = $tileHost;
        $this->mapId = $id;
        $this->attribution = $attribution;
        $this->leafletVersion = $leafletVersion;
    }

    public function render(): View
    {
        $markerArray = [];

        foreach ($this->markers as $marker) {
            $markerArray[] = [implode(",", $marker)];
        }
        if (!empty($this->polyLines))
            $polyLines = $this->polylinesToString();
        else
            $polyLines = null;

        return view('components.leaflet-map', [
            'polylines' => $polyLines,
            'centerPoint' => $this->centerPoint,
            'zoomLevel' => $this->zoomLevel,
            'maxZoomLevel' => $this->maxZoomLevel,
            'markers' => $this->markers,
            'markerArray' => $markerArray,
            'tileHost' => $this->tileHost,
            'mapId' => $this->mapId === self::DEFAULTMAPID ? Str::random() : $this->mapId,
            'attribution' => $this->attribution,
            'leafletVersion' => $this->leafletVersion ?? "1.7.1"
        ]);
    }

    private function polylinesToString()
    {
        $arr = [];
        foreach ($this->polyLines as $polyLine) {
            if ($polyLine['points']) {
                $polyLineStr = '[';
                foreach ($polyLine['points'] as $key => $point) {
                    /* @var Point $point */
                    $polyLineStr .= $point->getY() . ", " . $point->getX();
                    if ($key !== array_key_last($polyLine['points'])) {
                        $polyLineStr .= "],[";
                    }
                }
                $polyLineStr .= ']';
                $arr[] = $polyLineStr;
            }
        }
        return $arr;
    }
}
