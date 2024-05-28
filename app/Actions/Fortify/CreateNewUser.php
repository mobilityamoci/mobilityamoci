<?php

namespace App\Actions\Fortify;

use App\Enums\RolesEnum;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Laravel\Fortify\Contracts\CreatesNewUsers;
use Laravel\Jetstream\Jetstream;

class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules;

    /**
     * Validate and create a newly registered user.
     *
     * @param array<string, string> $input
     */
    public function create(array $input): User
    {
        $role = $input['role'] ?? null;
        Validator::make($input, [
            'name' => ['required', 'string', 'max:255'],
            'surname' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => $this->passwordRules(),
            'terms' => Jetstream::hasTermsAndPrivacyPolicyFeature() ? ['accepted', 'required'] : '',
            'school_id' => ['required', 'int', 'exists:schools,id'],
            'role' => [
                'string',
                'required',
                'exists:roles,name'
            ],
            'secret_password' => [
                'string',
                'required',
                function ($attribute, $value, $fail) use ($role) {
                    if ($role == RolesEnum::INSEGNANTE->value) {
                        if ($value !== config('auth.secret_password_teacher')) {
                            $fail('La Password Segreta non è valida.');
                        }
                    } else if ($role == RolesEnum::STUDENTE->value) {
                        if ($value !== config('auth.secret_password_student')) {
                            $fail('La Password Segreta non è valida.');
                        }
                    }
                }
            ]
        ])->validate();

        $user = User::create([
            'name' => $input['name'],
            'surname' => $input['surname'],
            'email' => $input['email'],
            'password' => Hash::make($input['password']),
        ]);

        $user->schools()->attach($input['school_id']);

        $user->assignRole($input['role']);

        return $user;
    }
}
