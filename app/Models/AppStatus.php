<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AppStatus extends Model
{
    protected $table = 'app_statuses';
    protected $guarded =[];

    public function appointments()
    {
        return $this->hasMany(Appointment::class,'status_id','id');
    }
}
