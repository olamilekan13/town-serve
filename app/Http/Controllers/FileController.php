<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Mail\PdfMail;
use Illuminate\Support\Facades\Mail;
use App\Models\File;

class FileController extends Controller
{
    public function upload(Request $request)

    {
        try{
            // Validate the incoming request
            $request->validate([
                'pdf_file' => 'required|mimes:pdf',
            ]);

            // Store the file
            $path = $request->file('pdf_file')->store('pdfs', 'public');

            // Save file information to the database
            $file = new File();
            $file->name = $request->file('pdf_file')->getClientOriginalName();
            $file->path = $path;
            $file->save();
            // dd($file);
            Mail::to('tmfbapplicationform@gmail.com')->send(new PdfMail($file->path,$file->name));

            return response()->json(['message' => 'File uploaded successfully']);

        }catch (\Exception $e){
        // Return Json Response
        return response()->json(['message' => $e->getMessage()],500);
        }
    }
}
