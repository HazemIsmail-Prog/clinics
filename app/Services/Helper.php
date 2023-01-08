<?php


namespace App\Services;


use App\Models\AppDepartment;
use App\Models\AppDevice;
use App\Models\AppStatus;
use App\Models\Clinic;
use App\Models\Department;
use App\Models\Doctor;
use App\Models\Nationality;
use App\Models\Nurse;
use App\Models\Patient;
use App\Models\Treatment;

class Helper
{

/*  Add the next line into your aliases array found in config/app.php
    'Helper' => App\Services\Helper::class
*/
    public static function clinic_is_deletable($id)
    {
        $current = Clinic::find($id);
        if ($current->app_departments->count() > 0 ||
            $current->appointments->count() > 0 ||
            $current->balances->count() > 0 ||
            $current->departments->count() > 0 ||
            $current->invoices->count() > 0 ||
            $current->offers->count() > 0 ||
            $current->patients->count() > 0 ||
            $current->users->count() > 0 ||
            $current->vouchers->count() > 0) {
            return false;
        } else {
            return true;
        }
    }


    public static function patient_is_deletable($id)
    {
        $current = Patient::find($id);
        if (
            $current->appointments->count() > 0 ||
            $current->invoices->count() > 0 ||
            $current->balances->count() > 0
        )
        {
            return false;
        } else {
            return true;
        }
    }


    public static function app_department_is_deletable($id)
    {
        $current = AppDepartment::find($id);
        if (
            $current->app_devices->count() > 0
        )
        {
            return false;
        } else {
            return true;
        }
    }


    public static function app_device_is_deletable($id)
    {
        $current = AppDevice::find($id);
        if (
            $current->appointments->count() > 0
        )
        {
            return false;
        } else {
            return true;
        }
    }

    public static function nationality_is_deletable($id)
    {
        $current = Nationality::find($id);
        if (
            $current->appointments->count() > 0 ||
            $current->patients->count() > 0
        )
        {
            return false;
        } else {
            return true;
        }
    }


    public static function app_status_is_deletable($id)
    {
        $current = AppStatus::find($id);
        if (
            $current->appointments->count() > 0
        )
        {
            return false;
        } else {
            return true;
        }
    }


    public static function nurse_is_deletable($id)
    {
        $current = Nurse::find($id);
        if (
            $current->appointments->count() > 0 ||
            $current->invoices->count() > 0

        )
        {
            return false;
        } else {
            return true;
        }
    }


    public static function doctor_is_deletable($id)
    {
        $current = Doctor::find($id);
        if (
            $current->invoices->count() > 0
        )
        {
            return false;
        } else {
            return true;
        }
    }

    public static function department_is_deletable($id)
    {
        $current = Department::find($id);
        if (
            $current->nurses->count() > 0 ||
            $current->doctors->count() > 0
        )
        {
            return false;
        } else {
            return true;
        }
    }

    public static function treatment_is_deletable($id)
    {
        $current = Treatment::find($id);
        if (
            $current->invoices->count() > 0
        )
        {
            return false;
        } else {
            return true;
        }
    }


}
