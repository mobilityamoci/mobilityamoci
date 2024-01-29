<?php

namespace App\Http\Livewire;


use App\Models\Building;
use Illuminate\Support\Facades\Cache;
use LivewireUI\Modal\ModalComponent;

class Buildings extends ModalComponent
{

    public $buildings;
    public int|null $selectedSchoolId;

    public bool $creatingBuilding = false;

    public string|null $newBuildingName = null;
    public string|null $newBuildingAddress = null;
    public int|null $newBuildingTownIstat = null;

    public int|null $editBuildingIndex = null;
    public string|null $editBuildingField = null;

    protected $rules = [
        'buildings.*.town_istat' => 'integer|required',
        'buildings.*.name' => 'string|required',
        'buildings.*.address' => 'string|required'
    ];

    public function mount(int $selectedSchoolId)
    {
        $this->selectedSchoolId = $selectedSchoolId;
        $this->reloadData();
    }

    public function render()
    {
        return view('livewire.buildings');
    }


    public function reloadData()
    {
        $this->buildings = Building::where('school_id', $this->selectedSchoolId)->get()->toArray();
    }

    public function setEditStudentField($index, $fieldName)
    {
        $this->editBuildingField = $index . '.' . $fieldName;
    }

    public function createBuilding()
    {
        $this->validate([
            'newBuildingName' => 'string|required',
            'newBuildingAddress' => 'string|required',
            'newBuildingTownIstat' => 'int|required',

        ]);

        $created = Building::create([
            'name' => $this->newBuildingName,
            'address' => $this->newBuildingAddress,
            'town_istat' => $this->newBuildingTownIstat,
            'school_id' => $this->selectedSchoolId
        ]);

        if (!$created) {
            session()->flash('error', 'Sede non creata');
        }

        $this->creatingBuilding = false;
        $this->newBuildingName = null;
        $this->newBuildingAddress = null;
        $this->newBuildingTownIstat = null;

        $this->reloadData();
    }

    public function deleteBuilding($index)
    {
        $building = $this->buildings[$index] ?? NULL;

        if (!is_null($building))
            optional(Building::find($building['id']))->delete();

        $this->reloadData();
    }

    public function setEditBuildingField($index, $fieldName)
    {
         $this->editBuildingField = $index . '.' . $fieldName;
    }

    public function saveBuilding($index)
    {
        $this->validate();
        $building = $this->buildings[$index] ?? NULL;

        if (!is_null($building))
            optional(Building::find($building['id']))->update($building);

        $this->editBuildingField = null;
        $this->editBuildingIndex = null;
    }

    public function getComuniProperty()
    {
        return getComuniArray();
    }

    /**
     * Supported: 'sm', 'md', 'lg', 'xl', '2xl', '3xl', '4xl', '5xl', '6xl', '7xl'
     */
    public static function modalMaxWidth(): string
    {
        return '5xl';
    }
}
