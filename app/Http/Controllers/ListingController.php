<?php

namespace App\Http\Controllers;

use App\Transactions;
use Illuminate\Http\Request;
use DB;
use Auth;


class ListingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = DB::table('listings')
            ->join('users','users.id', '=', 'listings.driver_id',)
            ->join('cars','cars.driver_id', '=', 'listings.driver_id')
            ->join('cities','cities.city_id','=','listings.city_id')
            ->select('listings.*','users.name','cars.*')
            ->where('listings.listing_status', '0') //select only 0 status listings
            ->get();
        
        return view('users.findcar', compact('data'));
    
    }

    public function search(Request $request)
    {
        $q = $request->get('q');
        $r = $request->get('r');
        $data = DB::table('listings')
        ->join('users','users.id', '=', 'listings.driver_id')
        ->join('cars','cars.driver_id', '=', 'listings.driver_id')
        ->join('cities','cities.city_id','=','listings.city_id')
        ->where('cars.type','like','%'.$r.'%')
        ->where('cities.city','like','%'.$q.'%')
        ->get();
        return view('users.search',compact('data'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\posted  $posted
     * @return \Illuminate\Http\Response
     */

    public function show(Request $request, $id)
    {
        $service_rate = 0.2;

        $data = DB::table('listings')
        ->join('cars','cars.car_id','=','listings.car_id')
        ->join('cities','cities.city_id','=','listings.city_id')
        ->join('users','users.id','=','listings.driver_id')
        ->select('listings.*','cars.*','users.*')
        ->where('listing_id','=', $id)->first();
        $existTxn = Transactions::where('passenger_id', '=', Auth::id())->where('status', '!=', '2')->orWhereNull('status')->first();
        
        $service_total = $data->rate * $service_rate;
        $service_charge = array('service_total'=>$service_total);

        return view('users.listing-details',compact(['data','service_charge', 'existTxn']));
    }

    public function store(Request $request)
    {
        $me = Auth::id();
        $transactions = new Transactions;

        $transactions->listing_id = $request->get('listing_id');
        $transactions->passenger_id = $me;
        $transactions->rent_start = $request->get('rent_start');
        $transactions->rent_end = $request->get('rent_end');
        $transactions->pickup_address = $request->get('pick_up');
        $transactions->dropoff_address = $request->get('drop_off');  
        $transactions->status = '2'; 

        $transactions->save();

        return back();  
    }
}
