<?php
namespace App\Http\Traits;

use App\Models\Account;
use App\Models\AccountLog;

trait AccountLogTrait
{
    private function GetBranchTreasury($treasury)
    {
        $branch_currency = Account::where('branch_id', auth()->user()->branch_id)->where('currency_id', $treasury)->first();
        if(is_null($branch_currency)){
            $branch_currency = Account::create([
                'branch_id' => auth()->user()->branch_id,
                'currency_id' => $treasury,
                'amount' => 0,
            ]);
        }
        return $branch_currency;
    }
    private function InsertAccountLog($account, $type, $amount, $desc, $available, $action='direct', $action_id=null, $date)
    {
        $flag = false;
        is_null($available) ? $available = 0 : $available = $available;
        $settings = request()->attributes->get('settings');
        $log = AccountLog::create([
            'account_id' => $account,
            'type' => $type,
            'amount' => $amount,
            'balance' => $available,
            'description' => $desc,
            'action' => $action,
            'action_id' => $action_id,
            'user_id' => auth()->user()->id,
            'branch_id' => auth()->user()->branch_id,
        ]);


        if($settings->date_type == 'shamsi'){
            $flag = $log->update([
                'shamsi_date' => $date,
            ]);
        }else{
            $flag = $log->update([
                'miladi_date' => $date,
            ]);
        }
        if($flag){
            return true;
        }else{
            return false;
        }

    }

}
