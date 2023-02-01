<?php

namespace App\Http\Livewire;

use App\Models\School;
use App\Models\Section;
use Livewire\Component;

class Sections extends Component
{

    public $sections;

    public int $selectedSchoolId;

    public int|null $editSectionIndex = null;

    public bool $creatingSection = false;

    public string|null $newSectionName = null;

    protected $rules = [
        'sections.*.name' => 'string|required|',
    ];

    public function mount()
    {
        $this->reloadSections();
    }

    public function render()
    {
        return view('livewire.sections');
    }

    public function reloadSections()
    {
        $this->sections = Section::where('school_id', $this->selectedSchoolId)->get()->toArray();
    }

    public function saveSection($index)
    {
        $this->validate();

        $section = $this->sections[$index] ?? NULL;

        if (!is_null($section))
            optional(Section::find($section['id']))->update($section);

        $this->editSectionIndex = null;

        $this->reloadSections();
    }

    public function startCreatingSection()
    {
        $this->creatingSection = true;
    }

    public function createSection()
    {

        $this->validate([
            'newSectionName' => 'string|required',
        ]);

        $created = Section::create([
            'name' => $this->newSectionName,
            'school_id' => $this->selectedSchoolId,
        ]);

        if (!$created) {
            session()->flash('error','Sezione non creata');
        }

        $this->creatingSection = false;

        $this->reloadSections();
    }

    public function deleteSection($index)
    {
        $section = $this->sections[$index] ?? NULL;

        if (!is_null($section))
            optional(Section::find($section['id']))->delete();

        $this->reloadSections();
    }
}
