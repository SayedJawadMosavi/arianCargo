<?php

namespace App\Http\Controllers;

use App\Models\Currency;
use App\Models\Rate;
use App\Models\Setting;
use Illuminate\Http\Request;
use App\Http\Requests\StoreRateRequest;
use App\Http\Requests\UpdateRateRequest;
use App\Models\Account;

class RateController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // $data  =[];
        // foreach (Currency::all() as $key ) {
        //      foreach (Currency::all() as $obj) {
        //          if ($key->id!=$obj->id) {
        //              $result = Rate::with('treasury', 'toTreasury')->where('from_treasury', $key->id)->where('to_treasury', $obj->id)->branch()->latest()->limit(1)->first();

        //              $data[$key->name][$obj->name]  =$result;
        //              is_null($result) ? 1 : $result;

        //          }
        //      }
        // }
    //    $data= Rate::with('treasury')->get();

       $data  =[];
       foreach (Currency::branch()->get() as $key ) {
            foreach (Currency::branch()->get() as $obj) {
                if ($key->id != $obj->id) {
                    $result = Rate::with('treasury')->where('from_treasury', $key->id)->where('to_treasury', $obj->id)->branch()->latest()->limit(1)->first();
                    $data[$key->name][$obj->name]  =$result;
                    is_null($result) ? 1 : $result;

                }
            }
       }

        $setting = Setting::where('branch_id', auth()->user()->branch_id)->first();
        // dd($setting);
        if(!$setting || !$setting->currency_id || $setting->currency_id == ''){
            // dd('here');
            return redirect()->back()->with('error', 'Please set your Base currency from the settings');
        }

        $currencies=   Currency::branch()->get();
        $to_currencies=   Currency::where('id',$setting->currency_id)->get();

        // dd($to_currencies);

        return view('rate.index',compact('currencies','data','to_currencies'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('rate.create')->with('currencies',  Currency::branch()->get());
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreRateRequest $request)
    {
         for ($i=0; $i <count($request->purchase) ; $i++) {
            if($request->rate[$i] <>0 && !is_null($request->rate[$i])){

                Rate::create([
                    'rate' => $request->rate[$i],
                    'operation' => $request->operation[$i],
                    'from_treasury' => $request->purchase[$i],
                    'to_treasury' => $request->sell[$i],
                    'user_id' => auth()->user()->id,
                    'branch_id' => auth()->user()->branch_id,

                ]);
            }
        }


        return redirect()->route('rate.index');

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Rate  $rate
     * @return \Illuminate\Http\Response
     */
    public function show(Rate $rate)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Rate  $rate
     * @return \Illuminate\Http\Response
     */
    public function edit(Rate $rate)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Rate  $rate
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateRateRequest $request, Rate $rate)
    {
        if ($request->ajax()) {

            Rate::find($request->pk)
            ->update([
                $request->name => $request->value,

            ]);

            return response()->json(['success' => true]);
        }
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Rate  $rate
     * @return \Illuminate\Http\Response
     */
    public function destroy(Rate $rate)
    {
        //
    }



    function get_latest_rate($account_id){

        $account = Account::find($account_id);

        $base_currency =  Setting::where('branch_id', auth()->user()->branch_id)->first();
        $branch_base = $base_currency->currency_id; //

        $result = Rate::where('from_treasury', $account->currency_id)->where('to_treasury', $branch_base)->branch()->latest()->limit(1)->first();
        if($result==null){
            $result =0;
        }

        if ($account->currency_id == $branch_base) {
            $rate = collect([
                'rate' => 1,
                'operation' => 'multiply',
            ]);
            return response()->json([
                'rate' => $rate
            ]);
        }

      return response()->json([
          'rate'  => $result,
        //   'from'  => $from,
        ]);

  }
    function getLatestRateFromToAccounts($from, $to){

        $from = Account::find($from);
        $to = Account::find($to);

        $result = Rate::where('from_treasury', $from->currency_id)->where('to_treasury', $to->currency_id)->branch()->latest()->limit(1)->first();
        if($result==null){
            $result =0;
        }

        if ($from->currency_id == $to->currency_id) {
            $rate = collect([
                'rate' => 1,
                'operation' => 'multiply',
            ]);
            return response()->json([
                'rate' => $rate
            ]);
        }

      return response()->json([
          'rate'  => $result,
        //   'from'  => $from,
        ]);

  }
}
