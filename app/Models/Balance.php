<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Balance extends Model
{
    protected $table='balances';
    protected $guarded = [];

    public function patient()
    {
        return $this->belongsTo(Patient::class,'patient_id','id');
    }

    public function invoice()
    {
        return $this->belongsTo(Invoice::class,'invoice_id','id');
    }

    public function scopeLoggedClinic($query)
    {
        return $query->whereClinicId(auth()->user()->clinic_id);
    }
}
