<table>
    <thead>
    <tr>
        <th>Mezzo di trasporto</th>
        <th>Distanza (in km)</th>
        <th>Carburante (g/anno)</th>
        <th>CO2 (g/anno)</th>
        <th>CO (g/anno)</th>
        <th>NOX (g/anno)</th>
        <th>PM10 (g/anno)</th>
        <th>KCal (KCal/anno)</th>
    </tr>
    </thead>
    <tbody>
    @if($res)
        @forelse($res as $trip)
            <tr>
                <td>{{$trip->transport_name}}</td>
                <td>{{number_format($trip->distance / 1000,2)}}</td>
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
