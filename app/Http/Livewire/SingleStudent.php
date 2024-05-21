<?php

namespace App\Http\Livewire;

use App\Models\Student;
use App\Models\User;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class SingleStudent extends Component
{

    use LivewireAlert;

    public User $user;

    public ?Student $student;
    private $component = 'single-student-create';
    protected $listeners = [
        'switch'
    ];

    public function mount(string $component = 'single-student-create')
    {
        $this->user = auth()->user();
        $this->student = $this->user->student;

        if ($this->student)
            $this->component = 'single-student-edit';
        else
            $this->component = $component;
    }

    public function render()
    {
        if ($this->component != 'single-student-edit' && $this->student)
            $this->component = 'single-student-edit';
        return view('livewire.single-student', ['component' => $this->component])->layout('layouts.student-layout');
    }

    public function switch(string $component)
    {
        $this->component = $component;
    }
}
