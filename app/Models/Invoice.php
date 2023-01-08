<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    protected $table='invoices';
    protected $guarded = [];
//    protected $appends = ['new_old'];

    public function clinic()
    {
        return $this->belongsTo(Clinic::class,'clinic_id','id');
    }

    public function patient()
    {
        return $this->belongsTo(Patient::class,'patient_id','id');
    }

    public function nurse()
    {
        return $this->belongsTo(Nurse::class,'nurse_id','id');
    }

    public function doctor()
    {
        return $this->belongsTo(Doctor::class,'doctor_id','id');
    }

    public function user()
    {
        return $this->belongsTo(User::class,'user_id','id');
    }

    public function invoice_details()
    {
        return $this->hasMany(InvoiceDetail::class,'invoice_id','id');
    }

    public function balance()
    {
        return $this->hasOne(Balance::class,'invoice_id','id');
    }

    public function treatments()
    {
        return $this->belongsToMany(Treatment::class,'invoice_details');
    }

    public function scopeLoggedClinic($query)
    {
//        return $query->whereClinicId(auth()->user()->clinic_id);
        return $query->whereHas('doctor',function ($q){
            $q->whereHas('department',function ($q2) {
                $q2->whereClinicId(auth()->user()->clinic_id);
            });
        });
    }

    public function getNotesAttribute($value)
    {
        return ucwords(strtolower($value));
    }

//    function getNewOldAttribute() {
//        if (date('Y-m-d' , strtotime($this->created_at))  == date('Y-m-d' , strtotime($this->patient->created_at)) )
//        {
//            return 'New';
//        }else{
//            return 'Old';
//        }
//    }


}
