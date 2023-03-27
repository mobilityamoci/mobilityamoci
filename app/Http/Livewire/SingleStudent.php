<?php

namespace App\Http\Livewire;

use App\Models\Student;
use App\Models\Transport;
use App\Models\Trip;
use App\Models\User;
use Illuminate\Support\Facades\Cache;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class SingleStudent extends Component
{

    use LivewireAlert;

    public User $user;

    public ?Student $student;

    protected $listeners = ['mount'];

    public function mount()
    {
        $this->user = auth()->user();
        $this->student = $this->user->student;
    }

    public function render()
    {
        return view('livewire.single-student');
    }
}
