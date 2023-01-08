<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AppDevice extends Model
{
    protected $table = 'app_devices';
    protected $guarded = [];

    public function app_department()
    {
        return $this->belongsTo(AppDepartment::class,'app_department_id','id');
    }

    public function appointments()
    {
        return $this->hasMany(Appointment::class,'device_id','id');
    }

    public function scopeLoggedClinic($query)
    {
        return $query->whereHas('app_department',function ($q){
            $q->whereClinicId(auth()->user()->clinic_id);
        });
    }

}
