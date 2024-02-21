<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Transport extends Model
{
    protected $guarded = ['id','created_at','updated_at'];

    public const PIEDI = 1;
    public const BICICLETTA = 2;
    public const BUS_COMUNALE = 3;
    public const AUTO = 4;
    public const AUTO_2 = 5;
    public const AUTO_3  = 6;
    public const TRENO = 7;

    public const MEZZI_PUBBLICI = [self::BUS_COMUNALE];

    public const MEZZI_PRIVATI = [self::PIEDI, self::BICICLETTA, self::AUTO, self::AUTO_2, self::AUTO_3];



    public function trips(): HasMany
    {
        return $this->hasMany(Trip::class);
    }
}
