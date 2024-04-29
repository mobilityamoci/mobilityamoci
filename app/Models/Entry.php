<?php

namespace App\Models;

class Entry extends \MattDaneshvar\Survey\Models\Entry
{
    protected $with = ['student'];

    public function student()
    {
        return $this->belongsTo(Student::class, 'participant_id');
    }
}
