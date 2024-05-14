<?php

namespace App\Http\Livewire;

use Auth;
use Livewire\Component;

class StudentNavigationMenu extends Component
{
    public function render()
    {
        return view('livewire.student-navigation-menu');
    }

    public function getSurveysCountProperty()
    {
        if (Auth::user()->student)
            return optional(Auth::user()->student->surveysToSubmit())->count();
        return 0;
    }
}
