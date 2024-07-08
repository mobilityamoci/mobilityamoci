<style>
    * {
        font-family: "Inter", sans-serif;
    }

    .color-primary {
        color: rgba(98, 101, 171, 1)
    }

    #container {
        text-align: center;
    }

    .mb {
        margin-bottom: 1.5rem;
    }

    .no-margin {
        margin: 0px auto;
    }

    img {
        border-radius: 15px;
    }

    .button {
        background-color: #6265AB;
        color: white;
        padding: 10px 20px;
        text-decoration: none;
        border-radius: 5px;
        display: inline-block;
        margin-TOP: 10px;
    }

    .button:hover {
        background-color: #494d92;
    }

    .support {
        margin-top: 30px;
        font-size: 0.8em;
    }

    .support p {
        margin: 0;
    }
</style>

<div id="container">
    <p>Ciao! Ecco il QR code per l'accesso sull'app <b class='color-primary'>Pedibus</b> di <b>{{ $student->name }} {{ $student->surname }}</b></p>

    <div id='content'>
        <img src="data:image/png;base64, {!! base64_encode(QrCode::format('png')->size(250)->backgroundColor(239, 245, 247)->margin(2)->gradient(98, 101, 171, 25,30,26, 'inverse_diagonal')->generate('$qrCode')) !!} ">
        
        <p class='mb'>Adesso puoi utilizzare questo QR code per entrare nella tua dashboard sull'app <b class='color-primary'>Pedibus</b></p>

        <p>Scansiona il codice con il tuo dispositivo mobile utilizzando l'app. <br> Potrai vedere il percorso che fa <b>{{ $student->name }}</b>, la fermata da cui parte, l'orario di partenza e altro. </p>
            
        <p class='no-margin'>Grazie per aver utilizzato il nostro servizio!</p>
            <br><br>
        <p>Cordiali saluti, <br>
        <b class='color-primary no-margin'>{{ config('app.name') }}</b></p>
        </p>
    </div>

    <div class="support">
        <p>Se hai bisogno di assistenza, non esitare a contattarci:</p>
        <p>Email: support@email.com</p>
        <p>Telefono: +393 45678 9099</p>
    </div>

    <div class="support">
        <p>Per ulteriori informazioni, visita il nostro sito web:</p>
        <a href="https://www.sito.com" class="button">Visita il sito</a>
    </div>    
</div>
