<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    protected $table = 'departments';
    protected $guarded = [];

    public function nurses()
    {
        return $this->hasMany(Nurse::class,'department_id','id');
    }

    public function treatments()
    {
        return $this->hasMany(Treatment::class,'department_id','id');
    }

    public function doctors()
    {
        return $this->hasMany(Doctor::class,'department_id','id');
    }

    public function clinic()
    {
        return $this->belongsTo(Clinic::class,'clinic_id','id');
    }

    public function invoices()
    {
        return $this->hasManyThrough(Invoice::class, Doctor::class);
    }

    public function scopeLoggedClinic($query)
    {
        return $query->whereClinicId(auth()->user()->clinic_id);
    }
}
