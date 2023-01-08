<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    protected $table = 'appointments';
    protected $guarded =[];

    public function creator()
    {
        return $this->belongsTo(User::class,'created_by','id');
    }

    public function updator()
    {
        return $this->belongsTo(User::class,'updated_by','id');
    }

    public function patient()
    {
        return $this->belongsTo(Patient::class,'patient_id','id');
    }

    public function app_device()
    {
        return $this->belongsTo(AppDevice::class,'device_id','id');
    }

    public function nurse()
    {
        return $this->belongsTo(Nurse::class,'nurse_id','id');
    }

    public function status()
    {
        return $this->belongsTo(AppStatus::class,'status_id','id');
    }

    public function nationality()
    {
        return $this->belongsTo(Nationality::class,'nationality_id','id');

    }

    public function scopeLoggedClinic($query)
    {
//        return $query->whereClinicId(auth()->user()->clinic_id);
        return $query->whereHas('app_device',function ($q){
            $q->whereHas('app_department',function ($q2) {
                $q2->whereClinicId(auth()->user()->clinic_id);
            });
        });
    }

    public function getNameAttribute($value)
    {
        return ucwords(strtolower($value));
    }

    public function getNotesAttribute($value)
    {
        return ucwords(strtolower($value));
    }

}
