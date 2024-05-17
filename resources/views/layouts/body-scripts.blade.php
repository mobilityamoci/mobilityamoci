@stack('modals')

@livewireScripts
<script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
@livewire('livewire-ui-modal')
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<x-livewire-alert::scripts/>
<!-- Make sure you put this AFTER Leaflet's CSS -->
<script src="{{'https://unpkg.com/leaflet@1.7.1/dist/leaflet.js'}}"
        crossorigin=""></script>
@stack('scripts')
