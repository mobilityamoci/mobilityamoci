<div style="padding-top: 3.5em; font-size: 1.1em">
    Ciao! Ecco il QR code per l'accesso sull'app di <b>{{ $student->name }} {{ $student->surname }}</b>:

    <br>
    <br>
    <div style="justify-content: center; align-content: center">
        <img src="data:image/png;base64, {!! base64_encode($qrCode) !!} ">

    </div>
</div>
