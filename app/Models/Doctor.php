<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Doctor extends Model
{
    protected $table = 'doctors';
    protected $guarded = [];

    public function department()
    {
        return $this->belongsTo(Department::class,'department_id','id');
    }

    public function account()
    {
        return $this->belongsTo(Account::class,'account_id','id');
    }

    public function invoices()
    {
        return $this->hasMany(Invoice::class,'doctor_id','id');
    }

    public function scopeLoggedClinic($query)
    {
        return $query->whereHas('department',function ($q){
            $q->whereClinicId(auth()->user()->clinic_id);
        });
    }
}
