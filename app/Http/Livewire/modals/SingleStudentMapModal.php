<?php

namespace App\Http\Livewire\modals;

use App\Models\Student;
use LivewireUI\Modal\ModalComponent;

class SingleStudentMapModal extends ModalComponent
{

    public int $studentId;

    public function render()
    {
        return view('livewire.single-student-map-modal');
    }

    public function mount(int $studentId)
    {
        $this->studentId = $studentId;
        $this->emitSelf('invalidateSize');
    }

    public function getStudentProperty()
    {
        return Student::find($this->studentId);
    }

    public function getWGS84PointProperty()
    {
        return $this->student->geometryPoint->getWGS84Point();
    }

    public function getWGS84SchoolPointProperty()
    {
        return $this->student->schoolGeometryPoint->getWGS84Point();
    }

    public function getStudentGeometryLineProperty()
    {
        return $this->student->trip1->geometryLine->toArrayWGS84();
    }

    public function getStudentPointLatProperty()
    {
        return $this->WGS84Point->getLatitude();
    }

    public function getStudentPointLonProperty()
    {
        return $this->WGS84Point->getLongitude();
    }

    public function getCenterPointProperty()
    {
        return ['lat' => $this->studentPointLat, 'lon' => $this->studentPointLon];
    }

    public function getSchoolProperty()
    {
        return $this->user->schools->first();
    }

    public function getSchoolPointLonProperty()
    {
        return $this->WGS84SchoolPoint->getLongitude();

    }

    public function getSchoolPointLatProperty()
    {
        return $this->WGS84SchoolPoint->getLatitude();
    }

    public function getSchoolCenterPointProperty()
    {
        return ['lat' => $this->schoolPointLat, 'lon' => $this->schoolPointLon];

    }

    public function getMarkersProperty()
    {
        return [array_merge($this->centerPoint, ['title' => 'Casa Mia']), array_merge($this->schoolCenterPoint, ['title' => 'Scuola'])];
    }

    public function getPolylinesProperty()
    {
        return [['points' => $this->studentGeometryLine]];
    }

    public static function modalMaxWidth(): string
    {
        return '7xl';
    }

}
