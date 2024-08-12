<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Position;
use App\Models\Staff;
use Illuminate\Http\Request;
use App\Http\Requests\StoreStaffRequest;
use App\Http\Requests\UpdateStaffRequest;
use App\Models\Account;
use App\Models\Currency;
use App\Models\StaffDepositWithdraw;
use Illuminate\Support\Facades\DB;

class StaffController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        return view('staff.index')->with('staffs', Staff::orderBy('id', 'desc')->get());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $pos = Staff::select('*')->distinct()->get();
        // $parent = Position::whereIn('id', $pos)->get();


        return view('staff.create')->with('pos', $pos);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreStaffRequest $request)
    {


        DB::beginTransaction();
        $files = $request->file('file');

        $file_path_name = "";
        if ($request->hasFile('file')) {
            foreach ($files as $f) {
                $extension_file = $f->getClientOriginalExtension();
                $dir_file = "storage/images/staffs/";
                $filename2 = uniqid() . '.' . $extension_file;
                $f->move($dir_file, $filename2);
                $file_path_name = $dir_file . $filename2 . ',' . $file_path_name;
            }
        }


        $staff = Staff::create([
            'name' => $request->name,
            'active' => 1,
            'fathername' => $request->father_name,
            'mobile' => $request->mobile,
            'address' => $request->address,
            'joining_date_shamsi' => $request->joining_date_shamsi,
            'joining_date_miladi' => $request->joining_date_miladi,
            'shamsi_date_dob' => $request->shamsi_date_dob,
            'miladi_date_dob' => $request->miladi_date_dob,
            'salary' => $request->salary,
            'education' => $request->education,
            'branch_id' => auth()->user()->branch_id,
            'tazkira_number' => $request->nid,
            'documents' => $file_path_name,
            'user_id' => auth()->user()->id,
            'position' => $request->position,
            'description' => $request->description,

        ]);


        if ($staff) {
            DB::commit();
            return redirect()->route('staff.index')->with('success', 'Staff created successfully');
        } else {
            DB::rollBack();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Staff  $staff
     * @return \Illuminate\Http\Response
     */
    public function show(Staff $staff)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Staff  $staff
     * @return \Illuminate\Http\Response
     */
    public function edit(Staff $staff)
    {
        return view('staff.create')->with('pos', Staff::select('*')->distinct()->get())->with('staff', $staff);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Staff  $staff
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateStaffRequest $request, Staff $staff)
    {
        \DB::beginTransaction();
        $files = $request->file('file');
        $images = $request->file('image');

        $file_path_name = "";
        $image = "";
        if ($request->hasFile('file')) {
            foreach ($files as $f) {
                $extension_file = $f->getClientOriginalExtension();
                $dir_file = "storage/images/staffs/";
                $filename2 = uniqid() . '.' . $extension_file;
                $f->move($dir_file, $filename2);
                $file_path_name = $dir_file . $filename2 . ',' . $file_path_name;
            }
        }
        if ($request->hasFile('image')) {

            $extension_file = $images->getClientOriginalExtension();
            $dir_file = "storage/images/staffs/";
            $filename2 = uniqid() . '.' . $extension_file;
            $images->move($dir_file, $filename2);
            $image = $dir_file . $filename2 . $image;
        }

        $staff = Staff::where('staff.id', $staff->id)->update([
            'name' => $request->name,
            'fathername' => $request->father_name,
            'mobile' => $request->mobile,

            'address' => $request->address,
            'joining_date_shamsi' => $request->joining_date_shamsi,
            'joining_date_miladi' => $request->joining_date_miladi,
            'shamsi_date_dob' => $request->shamsi_date_dob,
            'miladi_date_dob' => $request->miladi_date_dob,
            'salary' => $request->salary,
            'education' => $request->education,

            'tazkira_number' => $request->nid,
            'documents' => $file_path_name,

            // 'position_id' => $request->position,
            'position' => $request->position,
            'description' => $request->description,

        ]);


        if ($staff) {
            \DB::commit();
            return redirect()->route('staff.index')->with('success', ' Staff updated successfully');
        } else {
            \DB::rollBack();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Staff  $staff
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $staff = Staff::find($id);
        @unlink(public_path() . '/' . $staff->image);
        $staff->delete();
        return redirect()->route('staff.index')->with('success', ' staff deleted successfully');
    }
    public function forceDestroy($id)
    {
        $staff = Staff::find($id);
        @unlink(public_path() . '/' . $staff->image);

        Staff::withTrashed()->find($id)->forceDelete();
        return back()->with('success', 'staff deleted permanently');
    }
    public function restore($id)
    {
        Staff::withTrashed()->find($id)->restore();
        Staff::find($id)->update(['deleted_by' => 0]);
        return back()->with('success', 'staff Restored successfully');
    }

    public function get_documents($id)
    {
        $document = Staff::where('id', $id)->get();
        $documents = $document[0]->documents;
        $image = $document[0]->image;

        $data = $documents . $image;
        // dd($data);
        return Response($data);
    }
    public function insertSelectedFile(Request $request)
    {

        if (!$request->file) {
            return response()->json(['success' => 'file update without any changes .']);
        } else if (!$request->name) {
            $files = $request->file('file');
            if ($request->hasFile('file')) {
                $file_path_name = "";
                foreach ($files as $f) {
                    $extension_file = $f->getClientOriginalExtension();
                    $dir_file = "storage/images/staffs/";
                    $filename2 = uniqid() . '.' . $extension_file;
                    $f->move($dir_file, $filename2);
                    $file_path_name = $dir_file . $filename2 . ',' . $file_path_name;
                }
            }
            $oldFiles = Staff::select('documents')->where('id', $request->id)->first();
            $finalFiles = $oldFiles->documents . $file_path_name;

            $inserted = Staff::where('id', $request->id)->update(['documents' => $finalFiles]);
            if ($inserted) {
                return response()->json(['success' => 'new file inserted successfully .']);
            } else {
                return response()->json(['error' => 'file not inserted .']);
            }
        } else {
            return response()->json(['error' => 'file not inserted .']);
        }
    }



    public function deleteSelectedFile($name, $id)
    {
        $images = Staff::select('documents')->where('id', $id)->first();

        $image  = explode(",", $images);
        $selectedImage = "";
        $counter = 0;
        $updateDocument = $images->documents;
        if (count($image) <= 2) {
            $updated =  Staff::where('id', $id)->update(['documents' => ""]);
        } else {
            for ($i = 0; $i < count($image) - 1; $i++) {
                $image2  = explode("/", $image[$counter]);

                if ($image2[2] == $name) {
                    $selectedImage = $image2[2];
                }
                $counter++;
            }
            $newName = ',storage/images/staffs/' . $name;
            $updatedDocument = str_replace($newName, '', $updateDocument);
            $updated =  Staff::where('id', $id)->update(['documents' => $updatedDocument]);
        }
        $file_name = public_path() . '/storage/images/staffs/' . $selectedImage;
        \File::delete($file_name);
    }
    public function editSelectedFile(Request $request)
    {
        if (!$request->file || !$request->name) {
            return response()->json(['success' => 'file update without any changes .']);
        } else {
            $files = $request->file('file');
            $oldFiles = Staff::select('documents')->where('id', $request->id)->first();
            if ($request->hasFile('file')) {
                $file_path_name = $oldFiles->documents;
                foreach ($files as $f) {
                    $extension_file = $f->getClientOriginalExtension();
                    $dir_file = "storage/images/staffs/";
                    $filename2 = uniqid() . '.' . $extension_file;
                    $f->move($dir_file, $filename2);
                    $file_path_name = $dir_file . $filename2 . ',' . $file_path_name;
                }
            }
            // New
            $image  = explode(",", $oldFiles);
            $selectedImage = "";
            $counter = 0;
            $updateDocument = $oldFiles->documents;
            for ($i = 0; $i < count($image) - 1; $i++) {
                $image2  = explode("/", $image[$counter]);
                if ($image2[2] == $request->name) {
                    $selectedImage = $image2[2];
                }
                $counter++;
            }
            $newName = ',storage/images/staffs/' . $request->name;
            $file_path_name = str_replace($newName, '', $file_path_name);
            $updated =  Staff::where('id', $request->id)->update(['documents' => $file_path_name]);
            $file_name = public_path() . '/storage/images/staffs/' . $selectedImage;
            \File::delete($file_name);

            return response()->json(['success' => ' file updated successfully .']);
        }
    }

    public function statement($ids)
    {
        // dd('here');


        $stff_id  = StaffDepositWithdraw::where('staff_id', $ids)->with('staff')->first();
        $staff_loan=Staff::where('id',$ids)->first();
        $logs  = StaffDepositWithdraw::where('staff_id', $ids)->with('staff')->get();
        $accounts = Account::branch()->get();
        return view('staff_transaction.index', compact('logs', 'accounts', 'ids','staff_loan'));
    }
}
