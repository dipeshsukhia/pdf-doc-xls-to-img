<?php

namespace App\Http\Controllers;

use App\Upload;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Org_Heigl\Ghostscript\Ghostscript;
use Spatie\PdfToImage\Pdf;

class UploadController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = Upload::paginate(10);
        return view('upload.list',compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'file' => 'required'
        ]);
        $name = \Str::slug(basename($request->file->getClientOriginalName(), $request->file->getClientOriginalExtension())) . '-' . time();
        $filePath = Storage::disk('public')
            ->putFileAs(
                'uploads',
                $request->file('file'),
                $name.".".$request->file->getClientOriginalExtension()
            );
        $create = Upload::create([
           'name' => $filePath
        ]);
        //Ghostscript::setGsPath("C:\Program Files\gs\gs9.52\bin\gswin64c.exe");
        $pdf = new Pdf(storage_path('app/public/'.$filePath));
        foreach (range(1, $pdf->getNumberOfPages()) as $pageNumber) {
            $pdf->setPage($pageNumber)
                ->saveImage(storage_path('app/public/uploads/'.$name.'_page'.$pageNumber));
        }
        return redirect()->route('uploads.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Upload  $upload
     * @return \Illuminate\Http\Response
     */
    public function show(Upload $upload)
    {
        $name = pathinfo($upload->name, PATHINFO_FILENAME);
        foreach (glob(storage_path('app/public/uploads/'.$name.'_page*.jpeg')) as $file) {
            $files[] = $file;
        }
        dd($file);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Upload  $upload
     * @return \Illuminate\Http\Response
     */
    public function edit(Upload $upload)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Upload  $upload
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Upload $upload)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Upload  $upload
     * @return \Illuminate\Http\Response
     */
    public function destroy(Upload $upload)
    {
        //
    }
}
