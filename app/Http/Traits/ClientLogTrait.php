<?php
namespace App\Http\Traits;

use App\Models\BranchLog;
use App\Models\ClientLog;
use App\Models\BankLog;
use App\Models\BankTreasuryTransfer;
use App\Models\ClientCurrency;

trait ClientLogTrait
{

    private function InsertClientLog($client, $client_currency, $type, $amount, $desc, $available, $action='direct', $action_id=null, $date)
    {
        is_null($available) ? $available = 0 : $available = $available;

        $settings = request()->attributes->get('settings');
        $log = ClientLog::create([
            'client_id' => $client,
            'client_currency_id' => $client_currency,
            'type' => $type,
            'amount' => $amount,
            'available' => $available,
            'description' => $desc,
            'action' => $action,
            'action_id' => $action_id,
            'user_id' => auth()->user()->id,
        ]);
        if($settings->date_type == 'shamsi'){
            $log->update([
                'shamsi_date' => $date,
            ]);
        }else{
            $log->update([
                'miladi_date' => $date,
            ]);
        }
        if($log){
            return $log;
        }else{
            return false;
        }
    }

}
