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
    @if($canDraw)
    var drawnItems = new L.FeatureGroup();
    mymap.addLayer(drawnItems);
    var drawControl = new L.Control.Draw({
        draw: {
            polygon: false,
            rectangle: false,
            circle: false,
            @if(!$canDrawMarkers)
            marker: false,
            @endif
                @if(!$canDrawPolyLines)
            polyline: false,
            @endif
        },
        edit: {
            edit: true,
            featureGroup: drawnItems
        }
    });
    mymap.addControl(drawControl);

    mymap.on('draw:created', function (e) {
        var type = e.layerType,
            layer = e.layer;

        if (type === 'marker') {
            console.log(layer.getLatLng())
        } else {
            console.log(toWKT(layer))
            window.Livewire.emit('lineCreated', layer.getLatLngs())
        }
        drawnItems.addLayer(layer);
    });
    @endif
    @foreach($markers as $marker)
    @if(isset($marker['icon']))
    var icon = L.icon.glyph({prefix: 'fas', glyph: '{{$marker['icon']}}'});
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
    @if(isset($marker['title']))
    marker.bindPopup('{{$marker['title']}}');
    marker.openPopup();

    @endif
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
        console.log('resize')
        window.dispatchEvent(new Event('resize'));
    }, 500);


    function toWKT(layer) {
        var lng, lat, coords = [];
        if (layer instanceof L.Polygon || layer instanceof L.Polyline) {
            var latlngs = layer.getLatLngs();
            for (var i = 0; i < latlngs.length; i++) {
                latlngs[i]
                coords.push(latlngs[i].lng + " " + latlngs[i].lat);
                if (i === 0) {
                    lng = latlngs[i].lng;
                    lat = latlngs[i].lat;
                }
            };
            if (layer instanceof L.Polygon) {
                return "POLYGON((" + coords.join(",") + "," + lng + " " + lat + "))";
            } else if (layer instanceof L.Polyline) {
                return "LINESTRING(" + coords.join(",") + ")";
            }
        } else if (layer instanceof L.Marker) {
            return "POINT(" + layer.getLatLng().lng + " " + layer.getLatLng().lat + ")";
        }
    }
    // document.addEventListener(
    //     "rerender-map",
    //     (e) => {
    //         console.log('ziocan', e)
    //     },
    //     false,
    // );
</script>
