<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Nationality extends Model
{
    protected $table = 'nationalities';
    protected $guarded =[];

    public function appointments()
    {
        return $this->hasMany(Appointment::class,'nationality_id','id');
    }

    public function patients()
    {
        return $this->hasMany(Patient::class,'nationality_id','id');
    }

    public function getNameAttribute($value)
    {
        return ucwords(strtolower($value));
    }
}
