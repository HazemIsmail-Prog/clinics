<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Offer extends Model
{
    protected $table = 'offers';
    protected $guarded = [];

    public function scopeLoggedClinic($query)
    {
        return $query->whereClinicId(auth()->user()->clinic_id);
    }
}
