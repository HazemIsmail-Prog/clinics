<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Nurse extends Model
{
    protected $table = 'nurses';
    protected $guarded = [];

    public function department()
    {
        return $this->belongsTo(Department::class,'department_id','id');
    }

    public function appointments()
    {
        return $this->hasMany(Appointment::class,'nurse_id','id');
    }

    public function invoices()
    {
        return $this->hasMany(Invoice::class,'nurse_id','id');
    }

    public function scopeLoggedClinic($query)
    {
        return $query->whereHas('department',function ($q){
            $q->whereClinicId(auth()->user()->clinic_id);
        });
    }
}
