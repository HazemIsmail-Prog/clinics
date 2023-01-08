<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Clinic extends Model
{
    protected $table ='clinics';
    protected $guarded = [];


    public function account_group()
    {
        return $this->belongsTo(AccountGroup::class,'account_group_id');
    }

    public function app_departments()
    {
        return $this->hasMany(AppDepartment::class,'clinic_id','id');

    }

    public function appointments()
    {
        return $this->hasMany(Appointment::class,'clinic_id','id');

    }

    public function balances()
    {
        return $this->hasMany(Balance::class,'clinic_id','id');

    }

    public function departments()
    {
        return $this->hasMany(Department::class,'clinic_id','id');

    }

    public function invoices()
    {
        return $this->hasMany(Invoice::class,'clinic_id','id');

    }

    public function offers()
    {
        return $this->hasMany(Offer::class,'clinic_id','id');

    }

    public function patients()
    {
        return $this->hasMany(Patient::class,'clinic_id','id');

    }

    public function users()
    {
        return $this->hasMany(User::class,'clinic_id');

    }

    public function vouchers()
    {
        return $this->hasMany(Voucher::class,'clinic_id','id');

    }

    public function scopeLoggedClinic($query)
    {
        return $query->whereId(auth()->user()->clinic_id);
    }
}
