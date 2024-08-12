<?php
namespace App\Http\Traits;

use App\Models\VendorLog;

trait VendorLogTrait
{

    private function InsertVendorLog($vendor, $vendor_currency, $type, $amount, $desc, $available, $action='direct', $action_id=null, $date)
    {
        is_null($available) ? $available = 0 : $available = $available;
        $settings = request()->attributes->get('settings');
        $log = VendorLog::create([
            'vendor_id' => $vendor,
            'vendor_currency_id' => $vendor_currency,
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
