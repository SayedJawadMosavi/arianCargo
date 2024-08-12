<?php
namespace App\Http\Traits;


use App\Models\ClientCurrency;
use App\Models\ShareholderCurrency;
use App\Models\VendorCurrency;

trait CurrencyTrait
{
    private function GetClientCurrency($client, $treasury, $amount = 0)
    {
        $client_currency = ClientCurrency::where('client_id', $client)->where('currency_id', $treasury)->first();
        if(is_null($client_currency)){
            // dd('here1');
            $client_currency = ClientCurrency::create([
                'client_id' => $client,
                'currency_id' => $treasury,
                'amount' => $amount,
                'branch_id' => auth()->user()->branch_id,
            ]);
        }
        return $client_currency;
    }

    private function GetVendorCurrency($vendor, $treasury, $amount = 0)
    {
        $client_currency = VendorCurrency::where('vendor_id', $vendor)->where('currency_id', $treasury)->first();
        if(is_null($client_currency)){
            // dd('here1');
            $client_currency = VendorCurrency::create([
                'vendor_id' => $vendor,
                'currency_id' => $treasury,
                'amount' => $amount,
                'branch_id' => auth()->user()->branch_id,
            ]);
        }
        return $client_currency;
    }
    private function GetShareholderCurrency($vendor, $treasury, $amount = 0)
    {
        $client_currency = ShareholderCurrency::where('share_holders_id', $vendor)->where('currency_id', $treasury)->first();
        if(is_null($client_currency)){
            // dd('here1');
            $client_currency = ShareholderCurrency::create([
                'share_holders_id' => $vendor,
                'currency_id' => $treasury,
                'amount' => $amount,
                'branch_id' => auth()->user()->branch_id,
            ]);
        }
        return $client_currency;
    }

}
