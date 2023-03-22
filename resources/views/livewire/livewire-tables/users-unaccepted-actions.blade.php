<x-success-button wire:click.prevent="$emitUp('acceptUser',{{$id}})">Accetta Utente</x-success-button>
<x-jet-danger-button wire:click.prevent="$emitUp('rejectUser',{{$id}})">Rifiuta Utente</x-jet-danger-button>
