<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Gate;

class DayClosingController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('Reports_DayClosing'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('pages.day_closing.index');
    }

    public function print(Request $request)
    {
        abort_if(Gate::denies('Reports_DayClosing'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $this->validate($request,[
            'date' => 'required'
        ]);

        $date = $request->date;

        $invoices = Invoice::
        loggedClinic()
            ->whereUserId(auth()->id())
            ->whereDate('created_at',$request->date)
            ->orderBy('id')
            ->get();

        return view('pages.day_closing.print',compact('invoices','date'));
    }

}
