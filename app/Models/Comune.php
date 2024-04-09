<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Comune extends Model
{
    protected $table = 'limiti_comuni';
    protected $connection = 'basi_carto';

    protected $appends = ['labelSoundex'];


    public function getLabelSoundexAttribute()
    {
        return soundex($this->label);
    }

}
