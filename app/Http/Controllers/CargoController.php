<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCargoRequest;
use App\Http\Requests\UpdateCargoRequest;
use App\Http\Traits\AccountLogTrait;
use App\Http\Traits\ClientLogTrait;
use App\Http\Traits\CurrencyTrait;
use App\Models\Account;
use App\Models\AccountLog;
use App\Models\Cargo;
use App\Models\CargoDetail;
use App\Models\CargoPayment;
use App\Models\Client;
use App\Models\ClientCurrency;
use App\Models\ClientLog;
use App\Models\Country;
use App\Models\Currency;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

include "PersianCalendar.php";

class CargoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    use ClientLogTrait, CurrencyTrait, AccountLogTrait;

    protected $settings;
    public function __construct(Request $request)
    {
        $this->settings = $request->get('settings');
        $this->middleware('permission:sell.create', ['only' => ['create', 'store']]);
        $this->middleware('permission:sell.edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:sell.view', ['only' => ['index']]);
        $this->middleware('permission:sell.delete', ['only' => ['destroy']]);
        // $this->middleware('permission:sell.restore', ['only' => ['restore']]);
    }
    public function index()
    {
        if ($this->settings->date_type == 'shamsi') {
            $from =  datenow();
            $to =  datenow();
            $column = 'shamsi_date';
        } else {
            $from = date("Y-m-d");
            $to = date("Y-m-d");
            $column = 'miladi_date';
        }
        $cargos = Cargo::branch()->with('currency', 'receiver')->whereBetween($column, [$from, $to])->latest()->get();

        $trashed = Cargo::branch()->with('currency', 'receiver')->onlyTrashed()->whereBetween($column, [$from, $to])->get();
        return view('sell.index', compact('cargos', 'trashed'));
    }

    public function filterCargo(Request $request)
    {
        $from = isset($request->from_shamsi) ? $request->from_shamsi : $request->from_miladi;
        $to = isset($request->to_shamsi) ? $request->to_shamsi : $request->to_miladi;
        $column = isset($request->from_shamsi) ? $column = 'shamsi_date' : $column = 'miladi_date';

        $cargos = Cargo::branch()->with('currency', 'receiver')->whereBetween($column, [$from, $to])->latest()->get();
        $trashed = Cargo::branch()->with('currency', 'receiver')->onlyTrashed()->whereBetween($column, [$from, $to])->get();
        return view('sell.index', compact('cargos', 'trashed'));
    }
    public function bill($id)
    {
        $sell = Cargo::branch()->with('currency', 'receiver','payments','detail','client','branchs')->find($id);

        return view('sell.bill', compact('sell'));
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $clients = Client::branch()->get();
        $accounts = Account::branch()->orderBy('default', 'DESC')->get();
        // $products = Product::where('quantity', '>', 0)->branch()->get();
        $countries = Country::active()->get();

        $currencies = Currency::active()->branch()->get();
        return view('sell.create', compact('clients', 'accounts', 'currencies','countries'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreCargoRequest $request)
    {
        // dd($request->all());
        DB::beginTransaction();
        try {
            $balance = $request->total - $request->paid;
            $account = Account::find($request->account_id);

            $base_currency =  Setting::where('branch_id', auth()->user()->branch_id)->first();
            $branch_base = $base_currency->currency_id; //
            $sender = Client::find($request['client_id']);
            $receiver = Client::find($request['receiver_id']);
            $cargo = Cargo::create([
                'client_id' => $request->client_id,
                'account_id' => $request->account_id,

                'receiver_id' => $request->receiver_id,
                'currency_id' => $account->currency_id,
                'total_weight' => $request->total_weight,
                'per_weight' => $request->per_weight,
                'total' => $request->total,
                'paid' => $request->paid,
                'balance' => $balance,
                'rate' => $request->rate,
                'operation' => $request->operation ??  null,
                'bill' => $request->bill,
                'number' => $request->number,
                'description' => $request->description,
                'miladi_date' => $request->miladi_date,
                'shamsi_date' => $request->shamsi_date,
                'branch_id' => auth()->user()->branch_id,
                'user_id' => auth()->user()->id,
            ]);

            $item_name = $request->item_name;
            $quantity = $request->quantity;
            $cbm = $request->cbm;
            $type = $request->type;
            $values = $request->values;

            $to_currency_id = $request->to_currency_id;

            // $description = 'درک فروش: ' . $sell->id . ' - ' . $request->description;
            $description = ' Cargo Payment  : ' . $sender->name . ' - ' . $receiver->name . ' - ' . $request->description;

            // dd($product[0]);

            foreach ($item_name as $index => $item) {


                $sell_detail = CargoDetail::create([
                    'cargo_id' => $cargo->id,
                    'item_name' => $item_name[$index],
                    'quantity' => $quantity[$index],
                    'cbm' => $cbm[$index],
                    'type' => $type[$index],
                    'rate' => $request->rate ?? 1,
                    'item_value' => $values[$index],
                    'branch_id' => auth()->user()->branch_id,
                    'user_id' => auth()->user()->id,
                ]);
            }

            $type = 'deposit';
            // $currentDate = isset($request->date) ? $request->date :date('Y-m-d');
            $currentDate = isset($request->shamsi_date) ? $request->shamsi_date : $request->miladi_date;

            // Account::find($request->account_id)->increment('amount', $request->paid);
            if ($account->amount !== null) {
                // If amount is not null, add $request->paid to the existing amount
                $account->increment('amount', (float) $request->paid);
            } else {
                // If amount is null, set it to the value of $request->paid
                $account->update(['amount' => (float) $request->paid]);
            }

            $flag = $this->InsertAccountLog($request->account_id, $type, $request->paid, $description, $account->amount, 'cargo_payment', $cargo->id, $currentDate);
            // dd($flag);

            $curr = $this->GetClientCurrency($request->client_id, $account->currency_id, 0);
            $curr->decrement('amount', $request->total);

            $type = 'withdraw';
            $this->InsertClientLog($request->client_id, $curr->id, $type, $request->total, $description, $curr->amount, 'cargo_payment', $cargo->id, $currentDate);

            $curr->increment('amount', $request->paid);
            $this->InsertClientLog($request->client_id, $curr->id, 'deposit', $request->paid, $description, $curr->amount, 'cargo_payment', $cargo->id, $currentDate);
            $client_currency = ClientCurrency::where('client_id', $request->client_id)->first();

            if($request->amount >0){

                $payments = CargoPayment::create([
                    'account_id' => $cargo->account_id,
                    'cargo_id' => $cargo->id,
                    'client_id' => $cargo->client_id,
                    'amount' => $request->paid,
                    'client_currency_id' => $client_currency->id,
                    'description' => $description,
                    'shamsi_date' => $request->shamsi_date,
                    'miladi_date' => $request->miladi_date,
                    'type' => $type,
                    'branch_id' => auth()->user()->branch_id,
                    'user_id' => auth()->user()->id,
                ]);
            }

            if ($flag) {
                DB::commit();
                return redirect()->route('cargo.index')->with('success', 'Cargo stored successfully');
            } else {
                DB::rollBack();
                return redirect()->route('cargo.index')->with('error', 'Cargo Failed');
            }
        } catch (\Exception $e) {
            DB::rollBack();
            // Handle the exception
            return redirect()->back()->with('error', 'Error creating Cargo: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Cargo  $cargo
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $cargo = Cargo::with(['client', 'payments'])->findOrFail($id);

        return view('sell.payment', compact('cargo'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Cargo  $cargo
     * @return \Illuminate\Http\Response
     */
    public function edit(Cargo $cargo)
    {
        $cargo_client = Client::find($cargo->client_id);
        $clients = Client::branch()->get();
        $accounts = Account::branch()->where('currency_id', $cargo_client->currency->currency_id)->orderBy('default', 'DESC')->get();

        $currencies = Currency::active()->get();
        return view('sell.create', compact('clients', 'accounts', 'cargo', 'currencies'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Cargo  $cargo
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateCargoRequest $request, Cargo $cargo)
    {
        DB::beginTransaction();
        try {
            // dd($request->all());

            Account::find($cargo->account_id)->decrement('amount', $cargo->paid);
            AccountLog::where(['action_id' => $cargo->id, 'action' => 'cargo_payment', 'type' => 'deposit'])->delete();

            $currentDate = isset($request->shamsi_date) ? $request->shamsi_date : $request->miladi_date;

            Account::find($request->account_id)->increment('amount', $request->paid);
            $account = Account::find($request->account_id);

            $flag = $this->InsertAccountLog($request->account_id, 'deposit', $request->paid, $cargo->description, $account->amount, 'cargo_payment', $cargo->id, $currentDate);

            $log = ClientLog::where(['action_id' => $cargo->id, 'action' => 'cargo_payment'])->first();

            // dd($log);
            ClientCurrency::find($log->client_currency_id)->increment('amount', $cargo->total - $cargo->paid);
            ClientLog::where(['action_id' => $cargo->id, 'action' => 'cargo_payment'])->delete();


            $balance = $request->total - $request->paid;
            $flag = $cargo->update([
                'client_id' => $request->client_id,
                'receiver_id' => $request->receiver_id,
                'account_id' => $request->account_id,
                'total_weight' => $request->total_weight,
                'per_weight' => $request->per_weight,
                'total' => $request->total,
                'paid' => $request->paid,
                'balance' => $balance,
                'bill' => $request->bill,
                'description' => $request->description,
                'miladi_date' => $request->miladi_date,
                'shamsi_date' => $request->shamsi_date,
                'updated_by' => auth()->user()->id,
            ]);
            $sender = Client::find($request['client_id']);
            $receiver = Client::find($request['receiver_id']);
            $description = ' Cargo Payment  : ' . $sender->name . ' - ' . $receiver->name . ' - ' . $request->description;

            // NEW CLIENT CURRENCY AND LOG
            $curr = $this->GetClientCurrency($request->client_id, $account->currency_id, 0);
            $curr->decrement('amount', $request->total);

            $type = 'withdraw';
            $this->InsertClientLog($request->client_id, $curr->id, $type, $request->total, $description, $curr->amount, 'cargo_payment', $cargo->id, $currentDate);

            $curr->increment('amount', $request->paid);
            $this->InsertClientLog($request->client_id, $curr->id, 'deposit', $request->paid, $description, $curr->amount, 'cargo_payment', $cargo->id, $currentDate);

            if ($flag) {
                DB::commit();
                return redirect()->route('cargo.index')->with('success', 'Cargo updated successfully');
            } else {
                DB::rollBack();
                return redirect()->route('cargo.index')->with('error', 'Cargo update Failed');
            }
        } catch (\Exception $e) {
            DB::rollBack();
            // Handle the exception
            return redirect()->back()->with('error', 'Error updating Cargo: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Cargo  $cargo
     * @return \Illuminate\Http\Response
     */
    public function destroy(Cargo $cargo)
    {
        DB::beginTransaction();
        try {
            // Reverse the cargo payment effects
            foreach ($cargo->payments as $payment) {
                // Reverse from Account
                $account = Account::find($payment->account_id);
                if ($account) {
                    $account->decrement('amount', $payment->amount);
                }

                // Reverse from ClientCurrency
                $clientCurrency = ClientCurrency::find($payment->client_currency_id);
                if ($clientCurrency) {
                    $clientCurrency->decrement('amount', $payment->amount);
                }

                // Delete logs
                AccountLog::where('action', 'cargo_payment')->where('action_id', $payment->id)->delete();
                ClientLog::where('action', 'cargo_payment')->where('action_id', $payment->id)->delete();

                // Delete payment
                $payment->delete();
            }

            // Reverse product value from client currency
            $account = Account::find($cargo->account_id);
            if ($account) {
                $currencyId = $account->currency_id;
                $clientCurrency = $this->GetClientCurrency($cargo->client_id, $currencyId, 0);
                if ($clientCurrency) {
                    $clientCurrency->increment('amount', $cargo->total);
                }

                ClientLog::where('action', 'cargo_payment')->where('action_id', $cargo->id)->delete();
            }

            // Delete cargo details
            CargoDetail::where('cargo_id', $cargo->id)->delete();

            // Finally, delete the cargo
            $cargo->delete();

            DB::commit();
            return redirect()->route('cargo.index')->with('success', 'Cargo deleted successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Error deleting cargo: ' . $e->getMessage());
        }
    }

    public function restore($id)
    {
        DB::beginTransaction();
        try {
            $cargo = Cargo::withTrashed()->findOrFail($id);
            $cargo->restore();

            // Restore related cargo details
            CargoDetail::withTrashed()->where('cargo_id', $cargo->id)->restore();

            // Restore cargo payments and reverse accounting effects
            $payments = CargoPayment::withTrashed()->where('cargo_id', $cargo->id)->get();
            foreach ($payments as $payment) {
                $payment->restore();

                // Restore balances
                $account = Account::find($payment->account_id);
                if ($account) {
                    $account->increment('amount', $payment->amount);
                }

                $clientCurrency = ClientCurrency::find($payment->client_currency_id);
                if ($clientCurrency) {
                    $clientCurrency->decrement('amount', $cargo->balance);
                }

                // Restore logs
                AccountLog::withTrashed()->where([
                    'action' => 'cargo_payment',
                    'action_id' => $payment->id,
                ])->restore();

                ClientLog::withTrashed()->where([
                    'action' => 'cargo_payment',
                    'action_id' => $payment->id,
                ])->restore();
            }

            DB::commit();
            return redirect()->route('cargo.index')->with('success', 'Cargo restored successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Restore failed: ' . $e->getMessage());
        }
    }

    public function getCargoDetail(Cargo $cargo, $id)
    {
        $cargos = Cargo::find($id);
        $details = CargoDetail::where('cargo_id', $id)->get();
        $accounts = Account::branch()->orderBy('default', 'DESC')->get();
        return view('sell.detail', compact('cargos', 'details', 'accounts'));
    }
    public function pay(Request $request, $id)
    {
        $cargo = Cargo::findOrFail($id);
        $amount = floatval($request->input('pay_amount'));

        // Early validation
        if ($amount <= 0) {
            return back()->with('error', 'Payment amount must be greater than zero.');
        }

        if ($amount > $cargo->balance) {
            return back()->with('error', 'Payment amount exceeds the remaining balance.');
        }

        DB::beginTransaction();

        try {
            $account = Account::findOrFail($cargo->account_id);
            $client_currency = ClientCurrency::where('client_id', $cargo->client_id)->firstOrFail();
            $sender = Client::findOrFail($cargo->client_id);
            $receiver = Client::findOrFail($cargo->receiver_id);

            $description = 'Cargo Payment: ' . $sender->name . ' - ' . $receiver->name . ' - ' . $request->description;
            $type = 'deposit';
            $currentDate = $request->shamsi_date ?? $request->miladi_date;

            $payment = CargoPayment::create([
                'account_id' => $cargo->account_id,
                'cargo_id' => $cargo->id,
                'client_id' => $cargo->client_id,
                'amount' => $amount,
                'client_currency_id' => $client_currency->id,
                'description' => $description,
                'shamsi_date' => $request->shamsi_date,
                'miladi_date' => $request->miladi_date,
                'type' => $type,
                'branch_id' => auth()->user()->branch_id,
                'user_id' => auth()->user()->id,
            ]);

            // Update balances
            $account->increment('amount', $amount);
            $client_currency->increment('amount', $amount);

            // Insert logs
            $this->InsertAccountLog($account->id, $type, $amount, $description, $account->amount, 'cargo_payment', $payment->id, $currentDate);
            $this->InsertClientLog($cargo->client_id, $client_currency->id, $type, $amount, $description, $client_currency->amount, 'cargo_payment', $payment->id, $currentDate);

            // Update cargo
            $cargo->paid += $amount;
            $cargo->balance = $cargo->total - $cargo->paid;
            $cargo->save();

            DB::commit();

            return back()->with('success', 'Payment processed successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'An error occurred: ' . $e->getMessage());
        }
    }
    public function cargoDetailDelete(Request $request, $id)
    {

        DB::beginTransaction();
        try {

            $detail = CargoDetail::find($id);

            $detail->update([
                'deleted_by' =>auth()->user()->id,
            ]);
            $detail->delete();
            if ($detail) {
                DB::commit();
                return redirect()->back()->with('success', 'Item deleted successfully');
            } else {
                DB::rollBack();
                return redirect()->back()->with('error', 'Delete failed');
            }
        } catch (\Exception $e) {
            DB::rollBack();
            // Handle the exception
            return redirect()->back()->with('error', 'Error deleting sell item: ' . $e->getMessage());
        }
    }
    public function cargoDetailUpdate(Request $request)
    {

        DB::beginTransaction();
        try {
            $old = CargoDetail::find($request->id);
            // dd($request->all());
            $flag = $old->update(['quantity' => $request->quantity, 'cbm' => $request->cbm, 'type' => $request->type, 'item_name' => $request->item_name, 'item_value' => $request->item_value]);


            if ($flag) {
                DB::commit();
                return  response()->json(['success', 'updated successfully ']);
            } else {
                DB::rollBack();
                return  response()->json(['success', ' خطا در ویرایش ']);
            }
        } catch (\Exception $e) {
            DB::rollBack();
            // Handle the exception
            return redirect()->back()->with('error', 'Error updating Details: ' . $e->getMessage());
        }
    }
    public function cargoDetailInsert(Request $request, $id)
    {

        $flag = false;
        DB::beginTransaction();
        try {

            $item_name = $request->item_name;
            $quantity = $request->quantity;
            $cbm = $request->cbm;
            $type = $request->type;
            $values = $request->values;
            $to_currency_id = $request->to_currency_id;


            foreach ($item_name as $index => $item) {



                CargoDetail::create([
                    'cargo_id' => $id,
                    'item_name' => $item_name[$index],
                    'quantity' => $quantity[$index],
                    'cbm' => $cbm[$index],
                    'type' => $type[$index],
                    'rate' => $request->rate ?? 1,
                    'item_value' => $values[$index],
                    'branch_id' => auth()->user()->branch_id,
                    'user_id' => auth()->user()->id,
                ]);
            }
            $cargo = Cargo::find($id);
            $cargo->increment('total', $request->total);
            $cargo->increment('total_weight', $request->total_weight);
            $flag = $cargo->increment('balance', $request->total);

            // $currentDate = isset($request->date) ? $request->date :date('Y-m-d');
            $currentDate = isset($request->shamsi_date) ? $request->shamsi_date : $request->miladi_date;

            $curr = $this->GetClientCurrency($cargo->client_id, $cargo->account->currency_id, null);

            $curr->decrement('amount', $request->total);
            ClientLog::where(['action_id' => $cargo->id, 'action' => 'cargo_payment', 'type' => 'withdraw'])->delete();

            $type = 'withdraw';

            $this->InsertClientLog($cargo->client_id, $curr->id, $type, $cargo->total, $cargo->description, $curr->amount, 'cargo_payment', $cargo->id, $currentDate);

            // $curr->increment('amount', $request->paid);
            // $this->InsertClientLog($cargo->client_id, $curr->id, 'deposit', $cargo->paid, $cargo->description, $curr->amount, 'cargo', $cargo->id, $currentDate);

            if ($flag) {
                DB::commit();
                return redirect()->route('cargo.index')->with('success', 'cargo stored successfully');
            } else {
                DB::rollBack();
                return redirect()->route('cargo.index')->with('error', 'cargo Failed');
            }
        } catch (\Exception $e) {
            DB::rollBack();
            // Handle the exception
            return redirect()->back()->with('error', 'Error creating cargo: ' . $e->getMessage());
        }
    }
}
