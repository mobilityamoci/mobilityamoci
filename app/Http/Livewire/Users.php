<?php

namespace App\Http\Livewire;

use App\Models\Section;
use App\Models\User;
use App\Traits\WithAdminAuthorization;
use Illuminate\Support\Facades\Cache;
use Livewire\Component;

class Users extends Component
{

    use WithAdminAuthorization;

    protected $listeners = ['deleteUser'];

    protected $rules = [
        'users.*.name' => 'string|required',
        'users.*.surname' => 'string|required'
    ];


    public function render()
    {
        return view('livewire.users');
    }



}
