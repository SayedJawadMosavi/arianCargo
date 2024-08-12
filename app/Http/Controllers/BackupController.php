<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;

class BackupController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $databases = $this->backups();
        return view('backup.index',compact('databases'));
    }
    private function backups()
    {

        $filesInFolder = File::files(storage_path('app/Laravel'));
        $databases = [];
        foreach ($filesInFolder as $path) {
            $databases[] = $path;
        }

        return collect(array_reverse($databases))->sortByDesc(function ($database) {
            return $database->getATime();
        });
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

        try {
            Artisan::call('backup:run', ['--only-db' => true]);
            // Artisan::call('schedule:work');
            return redirect()->route('backups.index')
            ->with('success','Backup created successfully');
        } catch (\Exception $e) {

            return $e->getMessage();
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        try {
            Artisan::call('backup:run');
            return redirect()->route('backups.index')
                        ->with('success','Backup created successfully');
        } catch (\Exception $e) {

            return $e->getMessage();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($file_name)
    {
        $filePath = Storage::disk('local')->path('Laravel/'.$file_name);
        return response()->streamDownload(function () use ($filePath) {
            $fileStream = fopen($filePath, 'r');
            fpassthru($fileStream);
            fclose($fileStream);
        }, $file_name);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($file_name)
    {
        $file_names = \explode(',', $file_name);
            $filePath = Storage::disk('local')->path('Laravel/'.$file_name);
            $deleted= File::delete($filePath);
        if($deleted){
            return response()->json(['result'   =>      'success']);
        }else{
            return response()->json(['result'   =>      'error']);
        }
    }
}
