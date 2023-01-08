<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\Offer;
use App\Models\Patient;
use App\Models\User;
use App\Models\Voucher;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {

        $by_revenue = Invoice::loggedClinic()->where('created_at', '>=', Carbon::now()->subYear(1))->orderBy('created_at', 'asc')
            ->select(
                DB::raw("Year(created_at) as year"),
                DB::raw("Month(created_at) as month"),
                DB::raw('SUM(cash) + SUM(knet) + SUM(visa) + SUM(master) as total')
            )
            ->groupBy('year', 'month')
            ->get();

        $line_chart = [];
        foreach ($by_revenue as $row) {
            $line_chart [] = [
                'labels' => date('F, Y', strtotime('01-' . $row->month . '-' . $row->year)),
                'data' => $row->total
            ];
        }


        $this_week_birthdays = Patient::loggedClinic()->whereRaw(
            'DATE_ADD(patients.birthday,
                INTERVAL YEAR(CURDATE())-YEAR(birthday)
                         + IF(DAYOFYEAR(CURDATE()) > DAYOFYEAR(birthday),1,0)
                YEAR)
            BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL 7 DAY) '
        )
            ->orderBy(DB::raw('MONTH(birthday)'))
            ->orderBy(DB::raw('DAY(birthday)'))
            ->paginate(6);


        $error_vouchers = Voucher::
        loggedAccountGroup()
            ->whereHas('voucher_details', function ($q) {
                $q->select(DB::raw('SUM(debit) - SUM(credit) as diff'));
                $q->havingRaw('diff != 0');
            })
            ->with('voucher_details')
            ->get();


            $offers = Offer::query()
            ->where('start','<=',now())
            ->where('end','>=',now())
            ->get();

        return view('pages.dashboard.index', compact('line_chart', 'this_week_birthdays','error_vouchers','offers'));
    }

    public function change_clinic(Request $request)
    {
        User::whereId(1)->update(['clinic_id'=>$request->clinic_id]);
        return redirect()->back();
    }
}
