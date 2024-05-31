@stack('modals')

@livewireScripts
<script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
@livewire('livewire-ui-modal')
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<x-livewire-alert::scripts/>
<!-- Make sure you put this AFTER Leaflet's CSS -->
<script src="{{'https://unpkg.com/leaflet@1.7.1/dist/leaflet.js'}}"
        crossorigin=""></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet.draw/0.4.2/leaflet.draw.js"></script>
<script src="https://cdn.jsdelivr.net/npm/leaflet.icon.glyph@0.3.0/Leaflet.Icon.Glyph.min.js"></script>
@stack('scripts')
