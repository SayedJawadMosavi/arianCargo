<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreSettingRequest;
use App\Http\Requests\UpdateSettingRequest;
use App\Models\Currency;
use App\Models\Setting;
use App\Models\Subscribe;
use Illuminate\Http\Request;
use App\Models\Slider;

class SettingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreSettingRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreSettingRequest $request)
    {

        // dd($request->all());
        $photo = $request->logo;
        $bill_header_image = $request->bill_header;
        if ($request->hasFile('logo')) {
            if (file_exists(public_path() . '/' . $request->logo)) {
                @unlink(public_path() . '/' . $request->logo);
            }
            $fileName = date('YmdHis') . '_' . $request->name . '.' . $request->logo->extension();
            $request->logo->storeAs('images/setting', $fileName, 'public');
            $photo = 'storage/images/setting/' . $fileName;
        }
        if ($request->hasFile('bill_header')) {
            if (file_exists(public_path() . '/' . $request->bill_header)) {
                @unlink(public_path() . '/' . $request->bill_header);
            }
            $fileName = date('YmdHis') . '_' . $request->name . '.' . $request->bill_header->extension();
            $request->bill_header->storeAs('images/setting', $fileName, 'public');
            $bill_header_image = '/storage/images/setting/' . $fileName;
        }
        $registration_image = $request->registration_image;
        if ($request->hasFile('registration_image')) {
            if (file_exists(public_path() . '/' . $request->registration_image)) {
                @unlink(public_path() . '/' . $request->registration_image);
            }
            $fileName = date('YmdHis') . '_' . $request->name . '.' . $request->registration_image->extension();
            $request->registration_image->storeAs('images/setting', $fileName, 'public');
            $registration_image = '/storage/images/setting/' . $fileName;
        }
            isset($request->en) ? $en = 1 : $en = 0;
            isset($request->fa) ? $fa = 1 : $fa = 0;
            isset($request->pa) ? $pa = 1 : $pa = 0;

            $check = isset($request->check) ? 1 : 0;
            if (isset($request->en)) {
                $setting = Setting::create([

                    'en' => $en,
                    'fa' => $fa,
                    'pa' => $pa,

                ]);
            }else{
                Setting::create([
                    'user_id' => auth()->user()->id,
                    'logo' => $photo,
                    'bill_header' => $bill_header_image,
                    'registration_image' => $registration_image,
                    'name_en' => $request['name_en'],
                    'name_fa' => $request['name_fa'],
                    'name_pa' => $request['name_pa'],
                    'mobile1' => $request['mobile1'],
                    'mobile2' => $request['mobile2'],
                    'email' => $request['email'],
                    'address_en' => $request['address_en'],
                    'address_fa' => $request['address_fa'],
                    'address_pa' => $request['address_pa'],
                    'second_address' => $request['second_address'],
                    'facebook' => $request['facebook'],
                    'twitter' => $request['twitter'],
                    'youtube' => $request['youtube'],
                    'instagram' => $request['instagram'],
                    'linkedin' => $request['linkedin'],
                    'currency_id' => $request['currency_id'],
                    'meta_keyword' => $request['meta_keyword'],
                    'meta_description' => $request['meta_description'],
                    'date_type' => $request['date_type'],
                    'check' => $check,
                    'branch_id' => auth()->user()->branch_id,

                ]);
            }

        if (isset($request->en)) {
            return view('setting.language')->with('success', 'تنظیمات موفقانه ویرایش گردید.')->with('setting', Setting::branch()->get());
            # code...
        }else{
            $currencies = Currency::branch()->get();
            return view('setting.create')->with('success', 'تنظیمات موفقانه ویرایش گردید.')->with('currencies',$currencies)->with('setting', Setting::branch()->get());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Setting  $setting
     * @return \Illuminate\Http\Response
     */
    public function show(Setting $setting)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Setting  $setting
     * @return \Illuminate\Http\Response
     */
    public function edit(Setting $setting)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateSettingRequest  $request
     * @param  \App\Models\Setting  $setting
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateSettingRequest $request, Setting $setting)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Setting  $setting
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {

    }

    public function getSetting()
    {

        $currencies = Currency::branch()->get();
        return view('setting.create')->with('currencies',$currencies)->with('setting', Setting::where('branch_id', auth()->user()->branch_id)->first());
    }
    public function LanguageSetting()
    {

        return view('setting.language')->with('setting', Setting::branch()->get());
    }


    public function saveSetting(Request $request, Setting $setting)
    {
        // dd($request->all());
        $setting = Setting::find($request->id);
        $flag = false;
        $photo = $setting->logo;
        $bill_header_image = $setting->bill_header;
        if ($request->hasFile('logo')) {
            if (file_exists(public_path() . '/' . $setting->logo)) {
                @unlink(public_path() . '/' . $setting->logo);
            }
            $fileName = date('YmdHis') . '_' . $request->name . '.' . $request->logo->extension();
            $request->logo->storeAs('images/setting', $fileName, 'public');
            $photo = 'storage/images/setting/' . $fileName;
        }
        if ($request->hasFile('bill_header')) {
            if (file_exists(public_path() . '/' . $setting->bill_header)) {
                @unlink(public_path() . '/' . $setting->bill_header);
            }
            $fileName = date('YmdHis') . '_' . $request->name . '.' . $request->bill_header->extension();
            $request->bill_header->storeAs('images/setting', $fileName, 'public');
            $bill_header_image = '/storage/images/setting/' . $fileName;
        }
        $registration_image = $setting->registration_image;
        if ($request->hasFile('registration_image')) {
            if (file_exists(public_path() . '/' . $setting->registration_image)) {
                @unlink(public_path() . '/' . $setting->registration_image);
            }
            $fileName = date('YmdHis') . '_' . $request->name . '.' . $request->registration_image->extension();
            $request->registration_image->storeAs('images/setting', $fileName, 'public');
            $registration_image = '/storage/images/setting/' . $fileName;
        }
            isset($request->en) ? $en = 1 : $en = 0;
            isset($request->fa) ? $fa = 1 : $fa = 0;
            isset($request->pa) ? $pa = 1 : $pa = 0;

            $check = isset($request->check) ? 1 : 0;
            // dd($check);
            if (isset($request->en)) {
                $setting->update([

                    'en' => $en,
                    'fa' => $fa,
                    'pa' => $pa,

                ]);
            }else{

                // dd($setting);
               $flag =  $setting->update([
                    'user_id' => auth()->user()->id,
                    'logo' => $photo,
                    'bill_header' => $bill_header_image,
                    'registration_image' => $registration_image,
                    'name_en' => $request['name_en'],
                    'mobile1' => $request['mobile1'],
                    'mobile2' => $request['mobile2'],
                    'email' => $request['email'],
                    'address_en' => $request['address_en'],
                    'facebook' => $request['facebook'],
                    'twitter' => $request['twitter'],
                    'currency_id' => $request['currency_id'],
                    'date_type' => $request['date_type'],
                    'check' => $check,

                ]);
            }
            // dd($flag);
        if (isset($request->en)) {
            return view('setting.language')->with('success', 'تنظیمات موفقانه ویرایش گردید.')->with('setting', Setting::branch()->get());
            # code...
        }else{
            $currencies = Currency::branch()->get();
            return redirect()->route('setting.get')->with('success', 'تنظیمات موفقانه ویرایش گردید.');
        }
    }


}
