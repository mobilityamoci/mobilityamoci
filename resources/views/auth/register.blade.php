<x-guest-layout>
    <x-jet-authentication-card>
        <x-slot name="logo">
            <x-jet-authentication-card-logo/>
        </x-slot>

        <x-jet-validation-errors class="mb-4"/>

        <form method="POST" action="{{ route('register') }}">
            @csrf

            <div>
                <x-jet-label for="secret_password" value="Password Segreta (fornita dalla {{config('custom.lang.school')}})"/>
                <x-jet-input id="secret_password" class="block mt-1 w-full" type="password"
                             :value="old('secret_password')" name="secret_password" required/>
            </div>

            <div class="mt-4">
                <x-jet-label for="name" value="{{ __('Name') }}"/>
                <x-jet-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required
                             autofocus autocomplete="name"/>
            </div>

            <div class="mt-4">
                <x-jet-label for="surname" value="Cognome"/>
                <x-jet-input id="surname" class="block mt-1 w-full" type="text" name="surname" :value="old('surname')"
                             required autocomplete="surname"/>
            </div>

            <div class="mt-4">
                <x-jet-label for="school_id" value="Per quale scuola ti stai iscrivendo?"/>
                <x-select id="school_id" class="block mt-1 w-full" :value="old('school_id')" name="school_id"
                          required>
                    @foreach($schools as $school)
                        <option
                            @selected(old('school_id') == $school->id) value="{{$school->id}}">{{$school->name}}</option>
                    @endforeach
                </x-select>
            </div>

            <div class="mt-4">
                <x-jet-label for="role" value="Con quale ruolo ti stai iscrivendo?"/>
                <x-select id="role" class="block mt-1 w-full"
                          :value="old('role', \App\Enums\RolesEnum::STUDENTE->value)" name="role"
                          required>
                    <option
                        @selected(old('role', "Utente Base") == \App\Enums\RolesEnum::INSEGNANTE->value) value="{{\App\Enums\RolesEnum::INSEGNANTE->value}}">
                        Insegnante
                    </option>
                    <option
                        @selected(old('role', "Utente Base") == \App\Enums\RolesEnum::STUDENTE->value) value="{{\App\Enums\RolesEnum::STUDENTE->value}}">
                        Studente
                    </option>
                </x-select>
            </div>

            <div class="mt-4">
                <x-jet-label for="email" value="{{ __('Email') }}"/>
                <x-jet-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')"
                             required/>
            </div>

            <div class="mt-4">
                <x-jet-label for="password" value="{{ __('Password') }}"/>
                <x-jet-input id="password" class="block mt-1 w-full" type="password" name="password" required
                             autocomplete="new-password"/>
            </div>

            <div class="mt-4">
                <x-jet-label for="password_confirmation" value="{{ __('Confirm Password') }}"/>
                <x-jet-input id="password_confirmation" class="block mt-1 w-full" type="password"
                             name="password_confirmation" required autocomplete="new-password"/>
            </div>


            @if (Laravel\Jetstream\Jetstream::hasTermsAndPrivacyPolicyFeature())
                <div class="mt-4">
                    <x-jet-label for="terms">
                        <div class="flex items-center">
                            <x-jet-checkbox name="terms" id="terms" required/>

                            <div class="ml-2">
                                {!! __('I agree to the :terms_of_service and :privacy_policy', [
                                        'terms_of_service' => '<a target="_blank" href="'.route('terms.show').'" class="underline text-sm text-gray-600 hover:text-gray-900">'.__('Terms of Service').'</a>',
                                        'privacy_policy' => '<a target="_blank" href="'.route('policy.show').'" class="underline text-sm text-gray-600 hover:text-gray-900">'.__('Privacy Policy').'</a>',
                                ]) !!}
                            </div>
                        </div>
                    </x-jet-label>
                </div>
            @endif

            <div class="flex items-center justify-end mt-8">
                <a class="underline text-sm text-gray-600 hover:text-gray-900" href="{{ route('login') }}">
                    {{ __('Already registered?') }}
                </a>

                <x-jet-button class="ml-4">
                    {{ __('Register') }}
                </x-jet-button>
            </div>
        </form>
    </x-jet-authentication-card>
</x-guest-layout>
