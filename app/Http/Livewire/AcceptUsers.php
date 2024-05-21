<?php

namespace App\Http\Livewire;

use App\Models\School;
use App\Models\User;
use App\Notifications\AcceptedUserNotification;
use Livewire\Component;

class AcceptUsers extends Component
{
    public $user;

    public $schools;
    public int $selectedSchoolId;

    protected $listeners = ['acceptUser', 'rejectUser'];

    public function mount()
    {
        $this->user = \Auth::user();
        if ($this->user->can('all_schools')) {
            $this->schools = School::all();
        } else {
            $this->schools = $this->user->schools;
        }
        $this->selectedSchoolId = optional($this->schools->first())->id;
    }


    public function render()
    {
        return view('livewire.accept-users');
    }


    public function getUsersToAcceptProperty()
    {
        return School::find($this->selectedSchoolId)->usersToAccept()->with('roles')->get();
    }

    public function acceptUser($user_id)
    {
        $user = User::find($user_id);
        $user->update([
            'accepted_at' => now()
        ]);

        $this->emitTo('users-table','refreshDatatable');
//        $user->notify(new AcceptedUserNotification());
    }

    public function rejectUser($user_id)
    {
        User::find($user_id)->forceDelete();
        $this->emitTo('users-table','refreshDatatable');
    }
}
