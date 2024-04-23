<?php

namespace App\Traits;

use App\Models\School;

trait SelectedSchool
{

    public function getSelectedSchoolProperty()
    {
        return School::find($this->selectedSchoolId);
    }
}
