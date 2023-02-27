<?php

namespace App\Http\Livewire;

use App\Models\Building;
use App\Models\School;
use App\Models\Section;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use Livewire\Component;
use LivewireUI\Modal\ModalComponent;

class Sections extends ModalComponent
{

    public $sections;

    public $buildings;

    public int $selectedSchoolId;

    public int|null $editSectionIndex = null;
    public string|null $editSectionField = null;

    public bool $creatingSection = false;

    public string|null $newSectionName = null;
    public int|null $newBuildingId = null;

    protected $rules = [
        'sections.*.name' => 'string|required|',
        'sections.*.building_id' => 'int|exists:buildings,id'
    ];

    public function mount($selectedSchoolId)
    {
        $this->buildings = Building::where('school_id', $this->selectedSchoolId)->get()->keyBy('id')->toArray();
        $this->selectedSchoolId = $selectedSchoolId;
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
        $this->editSectionField = null;
    }

    public function startCreatingSection()
    {
        $this->creatingSection = true;
    }

    public function createSection()
    {
        $this->validate([
            'newSectionName' => 'string|required',
            'newBuildingId' => 'int|required'
        ]);

        $created = Section::create([
            'name' => $this->newSectionName,
            'school_id' => $this->selectedSchoolId,
            'building_id' => $this->newBuildingId
        ]);

        if (!$created) {
            session()->flash('error','Sezione non creata');
        }

        $this->creatingSection = false;
        $this->newSectionName = null;
        $this->newBuildingId = null;

        $this->reloadSections();
    }

    public function deleteSection($index)
    {
        $section = $this->sections[$index] ?? NULL;

        if (!is_null($section))
            optional(Section::find($section['id']))->delete();

        $this->reloadSections();
    }

    public function getComuniProperty()
    {
        return Cache::get('comuni');
    }

    /**
     * Supported: 'sm', 'md', 'lg', 'xl', '2xl', '3xl', '4xl', '5xl', '6xl', '7xl'
     */
    public static function modalMaxWidth(): string
    {
        return '4xl';
    }
}
