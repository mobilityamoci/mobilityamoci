<?php

namespace App\Observers;

use App\Models\Student;
use App\Services\QgisService;

class StudentsObserver
{
    private QgisService $qgisService;

    public function __construct(QgisService $qgisService)
    {
        $this->qgisService = $qgisService;
    }

    /**
     * Handle the Student "creating" event.
     *
     * @param Student $student
     * @return void
     */
    public function creating(Student $student)
    {
        $student = $this->qgisService::georefStudent($student);
    }

    /**
     * Handle the Student "updating" event.
     *
     * @param Student $student
     * @return void
     */
    public function updating(Student $student)
    {
        if ($student->isDirty(['address', 'town_istat'])) {
            $student = $this->qgisService::georefStudent($student);
        }
    }

    /**
     * Handle the Student "deleted" event.
     *
     * @param Student $student
     * @return void
     */
    public function deleted(Student $student)
    {
        //
    }

    /**
     * Handle the Student "restored" event.
     *
     * @param Student $student
     * @return void
     */
    public function restored(Student $student)
    {
        //
    }

    /**
     * Handle the Student "force deleted" event.
     *
     * @param Student $student
     * @return void
     */
    public function forceDeleted(Student $student)
    {
        //
    }
}
