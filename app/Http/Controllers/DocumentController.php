<?php

namespace App\Http\Controllers;

use App\Models\Document;
use App\Models\DocumentCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\StoreDocumentRequest;
use App\Http\Requests\UpdateDocumentRequest;
use Illuminate\Support\Facades\File;

include "PersianCalendar.php";

class DocumentController extends Controller
{
    protected $settings;
    public function __construct(Request $request)
    {

        $this->middleware('permission:document.create', ['only' => ['create', 'store']]);
        $this->middleware('permission:document.edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:document.delete', ['only' => ['destroy']]);
        // $this->middleware('permission:document.restore', ['only' => ['restore']]);
        $this->middleware('permission:document.forceDelete', ['only' => ['forceDelete']]);
        $this->settings = $request->get('settings');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
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
        $trashed = Document::branch()->onlyTrashed()->whereBetween($column, [$from, $to])->latest()->get();
        $documents = Document::branch()->whereBetween($column, [$from, $to])->latest()->orderBy('id', 'desc')->get();

        return view('document.index', compact('documents', 'trashed'));
    }
    public function filterDocument(Request $request)
    {
        $from = isset($request->from_shamsi) ? $request->from_shamsi : $request->from_miladi;
        $to = isset($request->to_shamsi) ? $request->to_shamsi : $request->to_miladi;
        $column = isset($request->from_shamsi) ? $column = 'shamsi_date' : $column = 'miladi_date';

        $documents = Document::branch()->whereBetween($column, [$from, $to])->latest()->get();
        $trashed = Document::branch()->onlyTrashed()->whereBetween($column, [$from, $to])->get();
        return view('document.index', compact('documents', 'trashed'));
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categories = DocumentCategory::where('status', 1)->get();

        return view('document.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreDocumentRequest $request)
    {
        DB::beginTransaction();
        try {
            $files = $request->file('file');

            $file_path_name = "";
            if ($request->hasFile('file')) {
                foreach ($files as $f) {
                    $extension_file = $f->getClientOriginalExtension();
                    $dir_file = "storage/images/document/";
                    $filename2 = uniqid() . '.' . $extension_file;
                    $f->move($dir_file, $filename2);
                    $file_path_name = $dir_file . $filename2 . ',' . $file_path_name;
                }
            }


            $document = Document::create([
                'description' => $request->name,
                'miladi_date' => $request->miladi_date,
                'shamsi_date' => $request->shamsi_date,
                'document_category_id' => $request->category_id,
                'file' => $file_path_name,
                'branch_id' => auth()->user()->branch_id,
                'user_id' => auth()->user()->id,
            ]);

            if ($document) {
                DB::commit();
                return redirect()->route('document.index')->with('success', 'document stored successfully');
            } else {
                DB::rollBack();
                return redirect()->route('document.index')->with('error', 'document Failed');
            }
        } catch (\Exception $e) {
            DB::rollBack();
            // Handle the exception
            return redirect()->back()->with('error', 'Error creating document: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Document  $document
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {

        $document = Document::where([
            'id'  => $id,

        ])->get();

        if (count($document) > 0) {
            $documents = $document[0]->file;
        } else {
            $documents  = "";
        }
        return Response($documents);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Document  $document
     * @return \Illuminate\Http\Response
     */
    public function edit(Document $document)
    {
        $categories = DocumentCategory::get();

        return view('document.create', compact('document', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Document  $document
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateDocumentRequest $request, Document $document)
    {
        DB::beginTransaction();
        try {

            $document->update([
                'description' => $request->name,
                'miladi_date' => $request->miladidate,
                'shamsi_date' => $request->shamsi_date,
                'document_category_id' => $request->category_id,

                'branch_id' => auth()->user()->branch_id,
                'user_id' => auth()->user()->id,
            ]);

            if ($document) {
                DB::commit();
                return redirect()->route('document.index')->with('success', 'document updated successfully');
            } else {
                DB::rollBack();
                return redirect()->route('document.index')->with('error', 'document Failed');
            }
        } catch (\Exception $e) {
            DB::rollBack();
            // Handle the exception
            return redirect()->back()->with('error', 'Error creating document: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Document  $document
     * @return \Illuminate\Http\Response
     */
    public function destroy(Document $document)
    {
        $document->delete();
        return redirect()->route('document.index')->with('success', 'Document Deleted');
    }

    public function restore($id)
    {

        Document::where('id', $id)->withTrashed()->restore();

        return redirect()->route('document.index')->with('success', 'Document Restored');
    }
    public function forceDelete($id)
    {


        Document::where('id', $id)->withTrashed()->forceDelete();

        return redirect()->route('document.index')->with('success', 'Document Force Deleted');
    }
    public function InsertFile(Request $request)
    {
        if (!$request->file) {
            return response()->json(['success' => 'File has been saved without any changes .']);
        } else if (!$request->name) {
            $files = $request->file('file');

            if ($request->hasFile('file')) {
                $file_path_name = "";
                foreach ($files as $f) {
                    $extension_file = $f->getClientOriginalExtension();
                    $dir_file = "storage/images/document/";
                    $filename2 = uniqid() . '.' . $extension_file;
                    $f->move($dir_file, $filename2);
                    $file_path_name = $dir_file . $filename2 . ',' . $file_path_name;
                }
            }
            $oldFiles = Document::select('file')->where('id', $request->id)->first();
            $finalFiles = $oldFiles->file . $file_path_name;

            $inserted = Document::where('id', $request->id)->update(['file' => $finalFiles]);
            if ($inserted) {
                return response()->json(['success' => 'file saved successfully .']);
            } else {
                return response()->json(['error' => 'sorry file not saved .']);
            }
        } else {
            return response()->json(['error' => 'sorry file not saved .']);
        }
    }
    public function editSelectedFile(Request $request)
    {
        if(!$request->file || !$request->name){
            return response()->json(['success' => 'File has been saved without any changes .']);
        }else{
            $files = $request->file('file');
            $oldFiles = Document::select('file')->where('id', $request->id)->first();
            if ($request->hasFile('file')) {
                $file_path_name = $oldFiles->file;
                foreach ($files as $f) {
                    $extension_file = $f->getClientOriginalExtension();
                    $dir_file = "storage/images/document/";
                    $filename2 = uniqid() . '.' . $extension_file;
                    $f->move($dir_file, $filename2);
                    $file_path_name = $dir_file . $filename2 . ',' . $file_path_name;
                }
            }
            // New
            $image  = explode(",", $oldFiles);
            $selectedImage = "";
            $counter = 0;
            $updateDocument = $oldFiles->file;
            for ($i=0; $i <count($image)-1 ; $i++) {
                $image2  = explode("/", $image[$counter]);
                if($image2[2] == $request->name){
                    $selectedImage = $image2[2];
                }
                $counter++;
            }
            $newName = ',storage/images/document/'.$request->name;
            $file_path_name = str_replace($newName, '', $file_path_name);
            $updated =  Document::where('id', $request->id)->update(['file' => $file_path_name]);
            $file_name= public_path() . '/storage/images/document/' .$selectedImage;
            \File::delete($file_name);

            return response()->json(['success' => 'file edited successfully .']);
        }
    }
    public function deleteSelectedFile($name, $id)
    {
        $images = Document::select('file')->where('id', $id)->first();

        $image  = explode(",", $images);
        $selectedImage = "";
        $counter = 0;
        $updateDocument = $images->file;
        if(count($image) <= 2){
            $updated =  Document::where('id', $id)->update(['file' => ""]);
        }else{
            for ($i=0; $i <count($image)-1 ; $i++) {
                $image2  = explode("/", $image[$counter]);

                if($image2[2] == $name){
                    $selectedImage = $image2[2];
                }
                $counter++;
            }
            $newName = ',storage/images/document/'.$name;
            $updatedDocument = str_replace($newName, '', $updateDocument);
            $updated =  Document::where('id', $id)->update(['file' => $updatedDocument]);

        }
        $file_name= public_path() . '/storage/images/document/' .$selectedImage;
        \File::delete($file_name);

    }
}
