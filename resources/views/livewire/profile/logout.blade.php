<div>
    <x-jet-form-section submit="logout">
        <x-slot name="title">
            Logout
        </x-slot>
        <x-slot name="description">
            Fai Logout dal portale.
        </x-slot>
        <x-slot name="form">
            @csrf
            <x-jet-button type="submit">
                {{ __('Log Out') }}
            </x-jet-button>
        </x-slot>


    </x-jet-form-section>
</div>
