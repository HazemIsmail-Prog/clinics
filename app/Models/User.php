<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    protected $table = 'users';
    protected $guarded = [];
    protected $hidden = ['password', 'remember_token'];

    public function created_appointments()
    {
        return $this->hasMany(Appointment::class,'created_by','id');
    }

    public function updated_appointments()
    {
        return $this->hasMany(Appointment::class,'updated_by','id');
    }

    public function clinic()
    {
        return $this->belongsTo(Clinic::class,'clinic_id');
    }

    public function hasPermission($permission)
    {
        $permissions = explode(',',$this->permissions);
        return in_array($permission, $permissions);
    }

    public function invoices()
    {
        return $this->hasMany(Invoice::class,'user_id');
    }

    public function patients()
    {
        return $this->hasMany(Patient::class,'user_id');
    }

    public function created_vouchers()
    {
        return $this->hasMany(Voucher::class,'created_by');
    }

    public function updated_vouchers()
    {
        return $this->hasMany(Voucher::class,'updated_by');
    }

    public function scopeLoggedClinic($query)
    {
        return $query->whereClinicId(auth()->user()->clinic_id);
    }





}
