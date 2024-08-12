<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreClientRequest;
use App\Http\Requests\UpdateClientRequest;
use App\Http\Traits\CurrencyTrait;
use App\Http\Traits\ClientLogTrait;
use App\Models\Client;
use App\Models\ClientCurrency;
use App\Models\ClientLog;
use App\Models\Currency;
use App\Models\Sell;
use App\Models\SellDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
include "PersianCalendar.php";

class ClientController extends Controller
{
    use CurrencyTrait, ClientLogTrait;
    protected $settings;

    public function __construct(Request $request)
    {
        $this->settings = $request->get('settings');
        $this->middleware('permission:client.view', ['only' => ['index', 'statement']]);
        $this->middleware('permission:client.create', ['only' => ['create', 'store']]);
        $this->middleware('permission:client.edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:client.delete', ['only' => ['destroy']]);
        $this->middleware('permission:client.restore', ['only' => ['restore']]);
        $this->middleware('permission:client.forceDelete', ['only' => ['forceDelete']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $deposit   = [];
        $withdraw   = [];
        if ($this->settings->date_type == 'shamsi') {
            $from =  datenow();
            $to =  datenow();
            $column = 'shamsi_date';
        } else {
            $from = date("Y-m-d");
            $to = date("Y-m-d");
            $column = 'miladi_date';
        }

        foreach (Currency::where('active', 1)->get() as $obj) {
            $sums[$obj->name] = ClientCurrency::where('currency_id', $obj->id)->branch()->sum('amount');
        }

        $clients = Client::with('currency')->branch()->get();
        // dd($clients->toArray());
        $trashed = Client::branch()->onlyTrashed()->get();
        return view('client.index', compact('clients', 'trashed', 'sums'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

        $currencies = Currency::active()->get();
        return view('client.create', compact('currencies'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreClientRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreClientRequest $request)
    {

        DB::transaction(function () use ($request, &$client) {

            $client = new Client();
            $attributes = $request->only($client->getFillable());
            $attributes['user_id'] = auth()->user()->id;
            $attributes['active'] = 1;
            $attributes['branch_id'] = auth()->user()->branch_id;
            $client =  $client->create($attributes);

            $treasuries = $request->treasury;
            $amount = $request->amount;

            // if (isset($treasuries)) {

            // foreach($treasuries as $index => $treasury) {
            $am = $amount;
            $description =  ' حساب ' . $request->name . ' افتتاح گردید ';
            // if(isset($amount) && $amount != 0) {
                $am > 0 ? $type = 'deposit' : $type = 'withdraw';
                $curr = $this->GetClientCurrency($client->id, $treasuries, $am);
                $this->InsertClientLog($client->id, $curr->id, $type, abs($am), $description, $curr->amount, 'client', null, $request->issue_date);
            // }
            // }
            // }
        });
        $clients = Client::branch()->get();
        $trashed = Client::branch()->onlyTrashed()->get();
        if ($request->from_sell == 1) {
            return redirect()->back()->with('Client registered successfully');
        }
        return redirect()->route('client.index', compact('clients', 'trashed'))->with('Client registered successfully');
    }
    public function clientClearanceSstore(Request $request)
    {

        $update = ClientLog::where('id', $request->id)->update([
            'clearance_description'   => $request->clearance_description,
            'clearance_date_shamsi'   => $request->clearance_date_shamsi,
            'clearance_date_miladi'   => $request->clearance_date_miladi,
        ]);

        if ($update) {

            return redirect()->back()->with('Clearnce description addedd successfully');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Client  $client
     * @return \Illuminate\Http\Response
     */
    public function show(Client $client)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Client  $client
     * @return \Illuminate\Http\Response
     */
    public function edit(Client $client)
    {
        $currencies = Currency::active()->get();
        return view('client.create', compact('client', 'currencies'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateClientRequest  $request
     * @param  \App\Models\Client  $client
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateClientRequest $request, Client $client)
    {
        $active = isset($request->active) ? 1 : 0;
        // dd($active);
        $attributes = $request->only($client->getFillable());
        $attributes['updated_by'] = auth()->user()->id;
        $attributes['active'] = $active;
        $attributes['branch_id'] = auth()->user()->branch_id;
        $client =  $client->update($attributes);

        $clients = Client::branch()->get();
        $trashed = Client::branch()->onlyTrashed()->get();
        return view('client.index', compact('clients', 'trashed'))->with('success', 'Client updated sucessfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Client  $client
     * @return \Illuminate\Http\Response
     */
    public function destroy(Client $client)
    {
        //
    }

    // public function statement(Request $request, Client $client){
    //     dd($client);

    //     // $from = isset($request->from) ? $request->from : datenow();
    //     $from = isset($request->from) ? $request->from : today();
    //     $to = isset($request->to) ? $request->to : today();


    //     $logs = ClientLog::with('client','client_currency')->whereBetween('shamsi_date', [$from, $to])
    //         ->where('client_id', $client->id)->get();

    //     return view('client.statement');
    // }

    public function statement(Client $client)
    {
        // dd($accountclient


        $logs = ClientLog::where('client_id', $client->id)->with('client', 'client_currency.currency')->get();
        // dd($logs);
        return view('client.statement', compact('logs', 'client'));
    }
    public function filterStatment(Request $request)
    {
        $from = isset($request->from_shamsi) ? $request->from_shamsi : $request->from_miladi;
        $to = isset($request->to_shamsi) ? $request->to_shamsi : $request->to_miladi;
        $column = isset($request->from_shamsi) ? $column = 'shamsi_date' : $column = 'miladi_date';

        $logs = ClientLog::where('client_id', $request->client_id)->with('client')->whereBetween($column, [$from, $to])->latest()->get();
        $client = Client::with('currency')->find($request->client_id);
        $client->client_logs = $logs;

        return view('client.statement', compact('logs', 'client'));
    }
    public function getSellDetail($id)
    {
        $sells = Sell::where('client_id', $id)->get();
        $sellIds = $sells->pluck('id')->toArray();
        $details = SellDetail::with('sell')->whereIn('sell_id', $sellIds)->get();

        return view('client.detail', compact('details'));
    }



    public function clientReceivable()
    {
        $deposit   = [];
        $withdraw   = [];

        foreach (Currency::where('active', 1)->get() as $obj) {
            $sums[$obj->name] = ClientCurrency::where('currency_id', $obj->id)->where('amount', '<', 0)->sum('amount');
        }

        $clients = Client::with('currency')->branch()->whereHas('currency', function($q){
            $q->where('amount', '<', 0);
        })->get();

        return view('client.receivable', compact('clients', 'sums'));
    }

    public function clientPayable()
    {
        $deposit   = [];
        $withdraw   = [];

        foreach (Currency::where('active', 1)->get() as $obj) {
            $sums[$obj->name] = ClientCurrency::where('currency_id', $obj->id)->where('amount', '>', 0)->sum('amount');
        }

        $clients = Client::with('currency')->branch()->whereHas('currency', function($q){
            $q->where('amount', '>', 0);
        })->get();

        return view('client.payable', compact('clients', 'sums'));
    }

}
