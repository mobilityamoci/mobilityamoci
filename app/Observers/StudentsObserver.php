<?php

namespace App\Observers;

use App\Models\Student;
use App\Services\QgisService;

class StudentsObserver
{
    public function __construct(QgisService $qgisService)
    {
        $this->qgisService = $qgisService;
    }

    /**
     * Handle the Student "created" event.
     *
     * @param Student $student
     * @return void
     */
    public function creating(Student $student)
    {
        $student->name = 'ti guardavo :)';
        $student = $this->qgisService::georefStudent($student);
    }

    /**
     * Handle the Student "updated" event.
     *
     * @param Student $student
     * @return void
     */
    public function updated(Student $student)
    {
        //
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
