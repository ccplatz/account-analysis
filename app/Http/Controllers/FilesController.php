<?php

namespace App\Http\Controllers;

use App\Models\File;
use Illuminate\Http\Request;
use App\Http\Requests\StoreFileRequest;
use Illuminate\Support\Facades\Storage;

class FilesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $files = File::all();

        return view('files.index', [
            'files' => $files
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  StoreFileRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreFileRequest $request)
    {
        $file = $request->file('file');
        $extension = $file->getClientOriginalExtension();
        $path = $file->storeAs(
            'transactions',
            substr(md5(microtime()), rand(0, 26), 5) . '_' . time() . '.' . $extension
        );
        $fileName = $file->getClientOriginalName();
        $size = $file->getSize();

        File::create([
            'path' => $path,
            'user_id' => auth()->id() ?? null,
            'name' => $fileName,
            'type' => $extension,
            'size' => $size
        ]);

        return redirect()->back()->withSuccess(__('File added successfully.'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(File $file)
    {
        Storage::delete($file->path);
        $file->delete();

        return redirect()->back()->withSuccess(__('File deleted.'));
    }
}