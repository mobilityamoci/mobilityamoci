<style>
    #{{$mapId}} {
    @if(! isset($attributes['style']))
        height: 75vh;
        width: 75vw;
    @else
        {{ $attributes['style'] }}
    @endif
    }
</style>

<div id="{{$mapId}}" @if(isset($attributes['class']))
    class='{{ $attributes["class"] }}'
    @endif
></div>

<script>

    var mymap = L.map('{{$mapId}}').setView([{{$centerPoint['lat'] ?? $centerPoint[0]}}, {{$centerPoint['lon'] ?? $centerPoint[1]}}], {{$zoomLevel}});
    @foreach($markers as $marker)
    @if(isset($marker['icon']))
    var icon = L.icon({
        iconUrl: '{{ $marker['icon'] }}',
        iconSize: [{{$marker['iconSizeX'] ?? 32}}, {{ $marker['iconSizeY'] ?? 32 }}],
    });
    @endif
    var marker = L.marker([{{$marker['lat'] ?? $marker[0]}}, {{$marker['lon'] ?? $marker[1]}}]
            @if(isset($marker['icon']) || isset($marker['title']))
            , {
                @if(isset($marker['icon']))
                icon: icon,
                @endif
                    @if(isset($marker['title']))
                title: "{{$marker['title']}}"
                @endif
            }
        @endif
                   );
    marker.addTo(mymap);
    @if(isset($marker['info']))
    marker.bindPopup(@json($marker['info']));
    @endif
    @endforeach

    var points;
    var latlngArr = [];
    @foreach($polylines as $polyline)

        points = [{{$polyline}}];
    latlngArr = [];
    points.forEach(function (coord) {
        latlngArr.push(L.latLng(coord[0], coord[1]))
    })
    var polyline{{$loop->iteration}} = L.polyline(latlngArr, {smoothFactor: 5}).addTo(mymap)
    @endforeach


    @if($tileHost === 'mapbox')
    let url{{$mapId}} = 'https://api.mapbox.com/styles/v1/{id}/tiles/{z}/{x}/{y}?access_token={{config('maps.mapbox.access_token', null)}}';
    @elseif($tileHost === 'openstreetmap')
    let url{{$mapId}} = 'https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png';
    @else
    let url{{$mapId}} = '{{$tileHost}}';
    @endif
    L.tileLayer(url{{$mapId}}, {
        maxZoom: {{$maxZoomLevel}},
        attribution: '{!! $attribution !!}',
        id: 'mapbox/streets-v11',
        tileSize: 512,
        zoomOffset: -1
    }).addTo(mymap);
    setTimeout(function () {
        window.dispatchEvent(new Event('resize'));
    }, 500);
</script>
