<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Treatment extends Model
{
    protected $table = 'treatments';
    protected $guarded = [];


    public function department()
    {
        return $this->belongsTo(Department::class,'department_id','id');
    }

    public function invoice_details()
    {
        return $this->hasMany(InvoiceDetail::class,'treatment_id','id');
    }

    public function invoices()
    {
        return $this->belongsToMany(Invoice::class,'invoice_details');
    }


    public function scopeLoggedClinic($query)
    {
        return $query->whereHas('department',function ($q){
            $q->whereClinicId(auth()->user()->clinic_id);
        });
    }

}
