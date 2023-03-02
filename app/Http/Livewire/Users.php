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

    public $users;

    public int|null $editUserIndex = null;
    public string|null $editUserField = null;


    protected $rules = [
        'users.*.name' => 'string|required',
        'users.*.surname' => 'string|required'
    ];

    public function mount()
    {
        $this->reloadUsers();
    }

    public function render()
    {
        return view('livewire.users');
    }

    public function reloadUsers()
    {
        $this->users = User::all()->toArray();
    }

    public function saveUser($index)
    {
        $this->validate();

        $user = $this->users[$index] ?? NULL;
        if (!is_null($user))
            optional(User::find($user['id']))->update($user);

        $this->editUserIndex = null;
        $this->editUserField = null;
    }

    public function deleteUser($index)
    {
        $user = $this->users[$index] ?? NULL;

        if (!is_null($user))
            optional(User::find($user['id']))->delete();

        $this->reloadUsers();
    }


}
