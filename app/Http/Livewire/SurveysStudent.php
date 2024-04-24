<?php

namespace App\Http\Livewire;

use Livewire\Component;

class SurveysStudent extends Component
{
    public function render()
    {
        return view('livewire.surveys-student')->layout('layouts.student-layout');
    }

    public function getSurveysProperty()
    {
        return \Auth::user()->student->surveys()->get();
    }
}
