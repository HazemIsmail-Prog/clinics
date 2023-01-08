<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AppDepartment extends Model
{
    protected $table = 'app_departments';
    protected $guarded = [];

    public function app_devices()
    {
        return $this->hasMany(AppDevice::class,'app_department_id','id');
    }

    public function clinic()
    {
        return $this->belongsTo(Clinic::class,'clinic_id','id');
    }

    public function scopeLoggedClinic($query)
    {
        return $query->whereClinicId(auth()->user()->clinic_id);
    }
}
