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
        // Validate the incoming request
        $request->validate([
            'pdf_file' => 'required|mimes:pdf|max:2048',
        ]);

        // Store the file
        $path = $request->file('pdf_file')->store('pdfs', 'public');

        // Save file information to the database
        $file = new File();
        $file->name = $request->file('pdf_file')->getClientOriginalName();
        $file->path = $path;
        $file->save();
        // dd($file);
        Mail::to('olamilekan1312@gmail.com')->send(new PdfMail($file->path));


        return response()->json(['message' => 'File uploaded successfully']);
    }
}
