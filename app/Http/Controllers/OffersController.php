<?php

namespace App\Http\Controllers;

use App\Models\Offer;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Gate;

class OffersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        abort_if(Gate::denies('Offers_Access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $offers = Offer::loggedClinic()->orderBy('id','desc')->get();
        return view('pages.offers.index',compact('offers'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        abort_if(Gate::denies('Offers_Create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('pages.offers.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        abort_if(Gate::denies('Offers_Create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $this->validate($request,[
           'start' => 'required | date',
           'end'=>  'required | date',
            'description' => 'required',
        ]);

        $data = [
            'start' => $request->start,
            'end' => $request->end,
            'description' => $request->description,
            'clinic_id' => auth()->user()->clinic_id,
        ];

        Offer::create($data);

        session()->flash('success', 'Offer Added Successfully');
        return redirect()->route('offers.index');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        abort_if(Gate::denies('Offers_Update'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $offer = Offer::loggedClinic()->findOrFail($id);
        return view('pages.offers.edit',compact('offer'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        abort_if(Gate::denies('Offers_Update'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $this->validate($request,[
            'start' => 'required | date',
            'end'=>  'required | date',
            'description' => 'required',
        ]);

        $offer = Offer::loggedClinic()->find($id);

        $data = [
            'start' => $request->start,
            'end' => $request->end,
            'description' => $request->description,
        ];

        $offer->update($data);

        session()->flash('success', 'Offer Updated Successfully');
        return redirect()->route('offers.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        abort_if(Gate::denies('Offers_Delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $offer = Offer::loggedClinic()->findOrFail($id);
        $offer->delete();
        session()->flash('success', 'Offer Deleted Successfully');
        return redirect()->route('offers.index');

    }
}
