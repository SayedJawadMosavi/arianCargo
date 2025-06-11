<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBranchRequest;
use App\Http\Requests\UpdateBranchRequest;
use App\Models\Branch;
use App\Models\Setting;

class BranchController extends Controller
{
    public function __construct()
    {

        $this->middleware('permission:branch.create', ['only' => ['create', 'store']]);
        $this->middleware('permission:branch.edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:branch.delete', ['only' => ['destroy']]);
        $this->middleware('permission:branch.restore', ['only' => ['restore']]);
        $this->middleware('permission:branch.forceDelete', ['only' => ['forceDelete']]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
public function index()
{
    // Eager load accounts with currency and branch
    $branches = Branch::with(['accounts.currency'])->get();

    $branchTotals = [];

    foreach ($branches as $branch) {
        $grouped = $branch->accounts->groupBy('currency_id');

        $totals = [];

        foreach ($grouped as $currencyId => $accounts) {
            $currency = $accounts->first()->currency;
            $totals[] = [
                'currency' => $currency->name,
                'total'    => $accounts->sum('cargo_amount'), // or 'amount'
                'paid_amount'    => $accounts->sum('paid_amount'), // or 'amount'
            ];
        }

        $branchTotals[$branch->id] = $totals;
    }

    return view('branch.index', compact('branches', 'branchTotals'));
}

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {


        return view('branch.create');
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreBranchRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreBranchRequest $request)
    {
        // 1. Validate bill range overlap
        $overlap = Branch::where(function ($query) use ($request) {
            $query->whereBetween('start_bill', [$request->start_bill, $request->end_bill])
                ->orWhereBetween('end_bill', [$request->start_bill, $request->end_bill])
                ->orWhere(function ($query) use ($request) {
                    $query->where('start_bill', '<=', $request->start_bill)
                        ->where('end_bill', '>=', $request->end_bill);
                });
        })->exists();

        if ($overlap) {
            return redirect()->back()->with('error', 'This bill number range overlaps with another branch.');
        }

        // 2. Upload image (if provided)
        $image_path_el = "";
        if ($request->hasFile('photo')) {
            $file = $request->file('photo');
            $filename = time() . '_' . $file->getClientOriginalName();
            $location = 'images/branch/';
            $image_path = $file->move($location, $filename);
            $image_path_el = $image_path;
        }

        // 3. Create branch
        if (($request->is_main_branch ?? 0) == 1) {
            $existingMainBranch = Branch::where('is_main_branch', 1)->first();

            if ($existingMainBranch) {
                return back()->with(['error' => 'You already have one main branch.']);
            }
        }
        $user = Branch::create([
            'name' => $request->name,
            'contact_person' => $request->contact_person,
            'is_main_branch' => $request->is_main_branch ?? 0,

            'mobile1' => $request->mobile1,
            'mobile2' => $request->mobile2,
            'address' => $request->address,
            'start_bill' => $request->start_bill,
            'end_bill' => $request->end_bill,
            'user_id' => auth()->user()->id,
            'logo' => $image_path_el,
        ]);

        // 4. Create branch setting (clone from main)
        $setting = Setting::with('currency')->first();
        $new_setting = $setting->replicate();
        $new_setting->branch_id = $user->id;
        $new_setting->check = 0;
        $new_setting->currency_id = null;
        $new_setting->save();

        // 5. Return with success
        return redirect()->route('branch.index')->with('success', 'New Branch added successfully');
    }


    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Branch  $branch
     * @return \Illuminate\Http\Response
     */
    public function show(Branch $branch)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Branch  $branch
     * @return \Illuminate\Http\Response
     */
    public function edit(Branch $branch)
    {

        return view('branch.create', compact('branch',));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateBranchRequest  $request
     * @param  \App\Models\Branch  $branch
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateBranchRequest $request, Branch $branch)
    {
        // 1. Check for bill range overlap excluding current branch
        $overlap = Branch::where('id', '!=', $branch->id)
            ->where(function ($query) use ($request) {
                $query->whereBetween('start_bill', [$request->start_bill, $request->end_bill])
                    ->orWhereBetween('end_bill', [$request->start_bill, $request->end_bill])
                    ->orWhere(function ($query) use ($request) {
                        $query->where('start_bill', '<=', $request->start_bill)
                            ->where('end_bill', '>=', $request->end_bill);
                    });
            })->exists();

        if ($overlap) {
            return redirect()->back()->with('error', 'This bill number range overlaps with another branch.');
        }


        // 2. Handle image upload
        $image_path_el = $branch->logo; // Keep existing if no new image
        if ($request->hasFile('photo')) {
            $file = $request->file('photo');
            $filename = time() . '_' . $file->getClientOriginalName();
            $location = 'images/branch/';
            $image_path = $file->move($location, $filename);
            $image_path_el = $image_path;
        }

        // 3. Update branch
        $branch->update([
            'name' => $request->name,
            'contact_person' => $request->contact_person,
            'mobile1' => $request->mobile1,
            'mobile2' => $request->mobile2,
            'address' => $request->address,
            'start_bill' => $request->start_bill,
            'is_main_branch' => $request->is_main_branch,

            'end_bill' => $request->end_bill,
            'user_id' => auth()->user()->id,
            'logo' => $image_path_el,
        ]);

        return redirect()->route('branch.index')->with('success', 'Branch updated successfully');
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Branch  $branch
     * @return \Illuminate\Http\Response
     */
    public function destroy(Branch $branch)
    {
        $branch->delete();
        return redirect()->route('branch.index')->with('success', ' Branch Deleted successfully');
    }
}
