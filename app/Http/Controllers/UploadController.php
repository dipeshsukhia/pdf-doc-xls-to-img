<?php

namespace App\Http\Controllers;

use App\Upload;
use Dompdf\Dompdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Org_Heigl\Ghostscript\Ghostscript;
use Spatie\PdfToImage\Pdf;
use PhpOffice\PhpWord;

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
        return view('upload.list', compact('data'));
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
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:pdf,doc,docx'
        ]);
        $name = \Str::slug(basename($request->file->getClientOriginalName(), $request->file->getClientOriginalExtension())) . '-' . time();
        $filePath = Storage::disk('public')
            ->putFileAs(
                'uploads',
                $request->file('file'),
                $name . "." . $request->file->getClientOriginalExtension()
            );
        $upload = Upload::create([
            'name' => $filePath
        ]);
        self::pdfToImg($upload->name);
        return redirect()->route('uploads.index');
    }

    public function pdfToImg($filePath)
    {
        $name = pathinfo($filePath, PATHINFO_FILENAME);
        $extension = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));
        $domPdfPath = base_path('vendor/dompdf/dompdf');

        switch ($extension) {
            case 'xls':
            case 'xlsx':
                $phpWord_xls = \PhpOffice\PhpSpreadsheet\IOFactory::load(storage_path('app/public/' . $filePath));
                $objWriter = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($phpWord_xls, 'Dompdf');
                $objWriter->save(storage_path("app/public/uploads/{$name}.pdf"));
                break;
            case 'doc':
            case 'docx':
                PhpWord\Settings::setPdfRendererPath($domPdfPath);
                PhpWord\Settings::setPdfRendererName('DomPDF');
                $phpWord = new PhpWord\PhpWord();
                $phpWord = PhpWord\IOFactory::load(storage_path('app/public/' . $filePath));
                $xmlWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'PDF');
                $xmlWriter->save(storage_path("app/public/uploads/{$name}.pdf"));
                break;
        }

        //Ghostscript::setGsPath("C:\Program Files\gs\gs9.52\bin\gswin64c.exe");
        if (file_exists(storage_path('app/public/uploads/' . $name . '.pdf'))) {
            $pdf = new Pdf(storage_path('app/public/uploads/' . $name . '.pdf'));
            foreach (range(1, $pdf->getNumberOfPages()) as $pageNumber) {
                $pdf->setPage($pageNumber)
                    ->saveImage(storage_path('app/public/uploads/' . $name . '_page' . $pageNumber . '.jpeg'));
            }
        }
    }

    public function getPdfImg($filePath)
    {
        $name = pathinfo($filePath, PATHINFO_FILENAME);
        $files = [];
        foreach (glob(storage_path('app/public/uploads/' . $name . '_page*.jpeg')) as $file) {
            $files[] = pathinfo($file, PATHINFO_BASENAME);
        }
        return $files;
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Upload $upload
     * @return \Illuminate\Http\Response
     */
    public function show(Upload $upload)
    {
        $files = self::getPdfImg($upload->name);
        return view('upload.show', compact('files'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Upload $upload
     * @return \Illuminate\Http\Response
     */
    public function edit(Upload $upload)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Upload $upload
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Upload $upload)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Upload $upload
     * @return \Illuminate\Http\Response
     */
    public function destroy(Upload $upload)
    {
        //
    }
}
