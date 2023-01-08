<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Patient extends Model
{
    protected $table = 'patients';
    protected $guarded = [];

    public function clinic()
    {
        return $this->belongsTo(Clinic::class, 'clinic_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function appointments()
    {
        return $this->hasMany(Appointment::class, 'patient_id', 'id');
    }

    public function invoices()
    {
        return $this->hasMany(Invoice::class, 'patient_id', 'id');
    }

    public function balances()
    {
        return $this->hasMany(Balance::class, 'patient_id', 'id');
    }

    public function nationality()
    {
        return $this->belongsTo(Nationality::class);
    }

    public static function search($search)
    {
        return empty($search) ? static::query()->where('clinic_id', auth()->user()->clinic_id)
            : static::query()
                ->where('clinic_id', auth()->user()->clinic_id)
                ->where(function ($q) use ($search) {
                    return $q->where('file_no', $search)
                        ->orWhere('name', 'like', $search . '%')
                        ->orWhere('mobile', 'like', $search . '%')
                        ->orWhere('civil_id', 'like', $search . '%');
                });



    }

    public function scopeLoggedClinic($query)
    {
        return $query->whereClinicId(auth()->user()->clinic_id);
    }

    public function getNameAttribute($value)
    {
        return ucwords(strtolower($value));
    }


}
