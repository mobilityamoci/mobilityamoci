<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasRoles;
    use HasApiTokens;
    use HasFactory;
    use Notifiable;
    use TwoFactorAuthenticatable;
    use HasProfilePhoto;
    use SoftDeletes;

   protected $guarded = ['id','created_at','updated_at'];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array<int, string>
     */
    protected $appends = [
        'profile_photo_url',
    ];


    public function schools(): MorphToMany
    {
        return $this->morphedByMany(School::class, 'associable','associables');
    }

    public function sections(): MorphToMany
    {
        return $this->morphedByMany(Section::class, 'associable','associables');
    }

    public function student()
    {
        return $this->hasOne(Student::class);
    }

    public function firstRoleString()
    {
        if (!is_null($this->roles)) {
            $role = optional($this->roles->first())->name;

            if ($role == 'Utente Base') {
                $role = 'Studente';
            }

            $school = $this->schools->first()->name;

            return $role . ' di ' . $school;
        } else {
            return '';
        }
    }

    public function homeRoute()
    {
        if ($this->hasAnyRole(['MMProvinciale','MMScolastico','Insegnante']))
        {
            return route('users');
        } else if ($this->hasAnyRole(['Utente Base'])) {
            return route('single-student');
        } else if ($this->hasRole('Admin')) {
            return route('users');
        }

        return route('logout');
    }
}
