# Mobilityamoci
## _La piattaforma per i mobility manager scolastici e aziendali_

### Descrizione estesa

Mobilitiamoci è una piattaforma web che aiuta nella loro operatività i **Mobility Manager aziendali e scolastici di ogni ordine e grado**.
La finalità è quella di calcolare i percorsi casa-scuola o casa-lavoro di studenti o dipendenti aiutando con mappe,
dati e grafici a valutarne l'impatto logistico e ambientale.
Attualmente il progetto è in una versione stabile e testata.

### Spiegazione struttra

Il repository si sviluppa su due branch: master e dev.
-  Il primo è utilizzato per tenere la versione stabile ed utilizzabile
-  Il secondo è usato per mandare avanti i lavori con versioni beta non testate e quindi meno stabili.

L'alberatura segue le linee guida di un tipico progetto [Laravel](https://laravel.com/).

### Dipendenze
L'applicazione è attualmente sviluppata con PHP 8.1, attraverso il framework [Laravel](https://laravel.com/) 9.52, sfruttando ampiamente LiveWire.
Utilizza un database PostgreSQL con le estensioni geografiche di PostGis e PgrRouting.
Necessita di un endpoint esterno di georeferenziazione spaziale basato su OpenStreetMap (es. [Nominatim](https://nominatim.openstreetmap.org/ui/search.html)).
Espone le mappe utilizzando un progetto QGis trasformato in interfaccia web da LizMap.
Le dipendenze di PHP e Node sono esplicitate nei file composer.json e package.json.


### Istruzioni per l'installazione



### Conclusione

Il Copyright è detenuto dal Comune di Piacenza.
La manutenzione è affidata a Brainfarm Soc. Coop.

Per ogni segnalazione relativa alla sicurezza scrivere a it@brainfarm.eu.
