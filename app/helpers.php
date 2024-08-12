<?php

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\User;
use App\Models\SellDetail;

    /**
 * Created by PhpStorm.
 * User: habib rahnam
 * Date: 05/23/2017
 * Time: 12:12 PM
 */

    function profit($sell){
        $sell = SellDetail::where('sell_id', $sell)->get();
        $profit = 0;
        foreach($sell as $obj){
            $profit += ($obj->quantity * $obj->cost) - ($obj->quantity * $obj->income_price);
        }
        return $profit;
    }

    function MainSubProductTotal($id){

        return 100;
    }

    function convert($string) {
        $persian = ['۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹'];
        $arabic = ['٩', '٨', '٧', '٦', '٥', '٤', '٣', '٢', '١','٠'];

        $num = range(0, 9);
        $convertedPersianNums = str_replace($persian, $num, $string);
        $englishNumbersOnly = str_replace($arabic, $num, $convertedPersianNums);

        return $englishNumbersOnly;
    }
// to convert date base on user setting or application lang
