<table>
    <thead>
    <tr>
        <th>Mezzo di trasporto</th>
        <th>Distanza (in metri)</th>
        <th>Carburante</th>
        <th>CO2</th>
        <th>CO</th>
        <th>NOX</th>
        <th>PM10</th>
        <th>KCal</th>
    </tr>
    </thead>
    <tbody>
    @if($res)
        @forelse($res as $trip)
            <tr>
                <td>{{$trip->transport_name}}</td>
                <td>{{$trip->distance}}</td>
                <td>{{$trip->carburante}}</td>
                <td>{{$trip->co2}}</td>
                <td>{{$trip->co}}</td>
                <td>{{$trip->nox}}</td>
                <td>{{$trip->pm10}}</td>
                <td>{{$trip->kcal}}</td>
            </tr>
        @empty
        @endforelse
    @endif
    </tbody>
</table>
