<?php
namespace App\Http\Traits;


use App\Models\ShareholderLog;

trait ShareholderLogTrait
{

    private function InsertShareholderLog($shareholder, $shareholder_currency, $type, $amount, $desc, $available, $action='direct', $action_id=null, $date)
    {
        is_null($available) ? $available = 0 : $available = $available;

        $settings = request()->attributes->get('settings');
        $log = ShareholderLog::create([
            'share_holder_id' => $shareholder,
            'shareholder_currency_id' => $shareholder_currency,
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
