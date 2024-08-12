<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\Assets;
use App\Models\Currency;
use App\Models\AssetsCategory;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Traits\AccountLogTrait;

class AssetsController extends Controller
{
    use AccountLogTrait;

    protected $settings;
    public function __construct(Request $request)
    {
        $this->settings = $request->get('settings');
        $this->middleware('permission:asset.create', ['only' => ['create', 'store']]);
        $this->middleware('permission:asset.edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:asset.delete', ['only' => ['destroy']]);
        $this->middleware('permission:asset.restore', ['only' => ['restore']]);
        $this->middleware('permission:asset.forceDelete', ['only' => ['forceDelete']]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('assets.index')->with('assets', Assets::with('assets_category')->branch()->get());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $base_currency =  Setting::where('branch_id', auth()->user()->branch_id)->first();
        $branch_base = $base_currency->currency_id; //
        $accounts = Account::branch()->where('amount','>',0)->orderBy('default', 'DESC')->get();

        return view('assets.create')->with('categories', AssetsCategory::get())->with('accounts', $accounts);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $assets = null;

        $request->validate([

            'name' => 'required',
            'asset_value' => 'required',
            'account_id' => 'required',
            'category_id' => 'required',
        ]);
        DB::transaction(function () use ($request, &$assets) {
            $account = Account::find($request->account_id);
            $assets = Assets::create([

                'name' => $request->name,
                'assets_value' => $request->asset_value,
                'quantity' => $request->quantity,
                'currency_id' => $account->currency_id,
                'category_id' => $request->category_id,
                'account_id' => $request->account_id,
                'miladi_date' => $request->miladidate,
                'shamsi_date' => $request->shamsi_date,
                'description' => $request->description,
                'user_id' => auth()->user()->id,
                'branch_id' => auth()->user()->branch_id,
            ]);
            if ($this->settings->date_type == 'shamsi') {
                $date = $request->shamsi_date;
            } else {
                $date =$request->miladi_date;
            }

            $currentDate = isset($request->shamsi_date) ? $request->shamsi_date : $request->miladi_date;


            $account->decrement('amount', $request->quantity * $request->asset_value);
            $flag = $this->InsertAccountLog($request->account_id, 'withdraw', $request->quantity * $request->asset_value, $request->description, $account->amount, 'assets', $assets->id, $currentDate);
        });
        if ($assets) {

            return redirect()->route('asset.index')->with('success', 'Assets added successfully');
        } else {

            return redirect()->route('asset.index')->with('error', 'expense Failed');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Assets  $assets
     * @return \Illuminate\Http\Response
     */
    public function show(Assets $assets)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Assets  $assets
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $assets = Assets::find($id);

        return view('assets.create')->with('categories', AssetsCategory::get())->with('asset', $assets)->with('currencies', Currency::active()->get());
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Assets  $assets
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([

            'name' => 'required',
            'asset_value' => 'required',
            'currency_id' => 'required',
            'category_id' => 'required',
        ]);

        $assets = Assets::where('id', $id)->update([

            'name' => $request->name,
            'assets_value' => $request->asset_value,
            'quantity' => $request->quantity,
            'currency_id' => $request->currency_id,
            'category_id' => $request->category_id,
            'miladi_date' => $request->miladidate,
            'shamsi_date' => $request->shamsi_date,
            'description' => $request->description,
            'user_id' => auth()->user()->id,
            'branch_id' => auth()->user()->branch_id,
        ]);



        if ($assets) {

            return redirect()->route('asset.index')->with('success', 'Assets Updated successfully');
        } else {

            return redirect()->route('asset.index')->with('error', 'Assets Failed');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Assets  $assets
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Assets::find($id)->delete();
        return redirect()->route('asset.index')->with('success', 'Assets Updated successfully');
    }
}
