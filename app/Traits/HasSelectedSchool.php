<?php

namespace App\Traits;

use App\Models\School;

trait HasSelectedSchool
{
    public $selectedSchoolId;


    public function mount()
    {
        if (!$this->selectedSchoolId && $this->schools->first()) {
            $this->selectedSchoolId = $this->schools->first()->id;
        }
        return null;
    }

    public function getSchoolsProperty()
    {
        return getUserSchools(true);
    }

    public function getSelectedSchoolProperty()
    {
        if ($this->selectedSchoolId)
            return School::find($this->selectedSchoolId);
        else {
            return $this->schools->first();
        }
    }

    public function schoolChanged()
    {

    }
}
