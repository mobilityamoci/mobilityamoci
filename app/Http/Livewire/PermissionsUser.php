<?php

namespace App\Http\Livewire;

use App\Models\School;
use App\Models\Section;
use App\Models\User;
use LivewireUI\Modal\ModalComponent;
use Spatie\Permission\Models\Role;


class PermissionsUser extends ModalComponent
{

    public int $selectedUserId;
    public $user;

    public $allRoles;

    public bool $addingNewRole = false;
    public bool $addingNewSchool = false;
    public bool $addingNewSection = false;

    public $newRole;
    public int|null $newSchoolId = null;
    public int|null $newSectionId = null;

    public function mount($selectedUserId)
    {
        $this->allRoles = Role::all();
        $this->selectedUserId = $selectedUserId;
        $this->user = User::with('roles', 'schools', 'sections')->find($this->selectedUserId);
    }

    public function render()
    {
        return view('livewire.permissions-user');
    }

    public function getUserIsInsegnanteProperty()
    {
        return $this->userRoles->contains(function (Role $role) {
            return $role->name == 'Insegnante';
        });
    }

    public function getAllSchoolsProperty()
    {
        $selectedUserId = $this->selectedUserId;
        return School::whereDoesntHave('users', function($q) use ($selectedUserId) {
            $q->where('user_id', $selectedUserId);
        })->get();
    }

    public function getAllSectionsProperty()
    {
        $selectedUserId = $this->selectedUserId;
        return Section::whereIn('school_id', $this->userSchools->pluck('id')->toArray())
            ->whereDoesntHave('users', function($q) use ($selectedUserId) {
            $q->where('user_id', $selectedUserId);
        })->get();
    }

    public function getUserIsMMScolasticoProperty()
    {
        return $this->userRoles->contains(function (Role $role) {
            return $role->name == 'MMScolastico';
        });
    }

    public function getUserRolesProperty()
    {
        return $this->user->roles;
    }

    public function getUserSchoolsProperty()
    {
        return $this->user->schools()->get();
    }

    public function getUserSectionsProperty()
    {
        return $this->user->sections()->get();
    }

    public function removeRole($role_name)
    {
        $this->user->removeRole($role_name);
    }

    public function saveNewRole()
    {
        $this->user->assignRole($this->newRole);
        $this->addingNewRole = false;
    }

    public function removeSchool($school_id)
    {
        $sections = Section::where('school_id',$school_id)->get();
        $this->user->sections()->detach($sections);
        $this->user->schools()->detach($school_id);
    }

    public function saveNewSchool()
    {
        if ($this->newSchoolId != null) {
            User::find($this->user->id)->schools()->attach($this->newSchoolId);
            $this->addingNewSchool = false;
            $this->newSchoolId = null;
        }
    }

    public function removeSection($school_id)
    {
        $this->user->sections()->detach($school_id);
    }

    public function saveNewSection()
    {
        if ($this->newSectionId != null) {
            User::find($this->user->id)->sections()->attach($this->newSectionId);
            $this->addingNewSection = false;
            $this->newSectionId = null;
        }
    }


    public function hydrate()
    {
        $this->user->load('sections');
        $this->user->load('schools');
    }

    /**
     * Supported: 'sm', 'md', 'lg', 'xl', '2xl', '3xl', '4xl', '5xl', '6xl', '7xl'
     */
    public static function modalMaxWidth(): string
    {
        return '4xl';
    }

}
