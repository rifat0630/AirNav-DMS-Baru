<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Document;
use App\Services\GoogleDriveService;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;


class DocumentController extends Controller
{


    public function index(Request $request)
    {
        $query = Document::with(['category','user']);


        if ($request->filled('search')) {

            $query->where(function ($q) use ($request) {

                $q->where(
                    'document_number',
                    'like',
                    '%' . $request->search . '%'
                )
                ->orWhere(
                    'title',
                    'like',
                    '%' . $request->search . '%'
                );

            });

        }


        if ($request->filled('category')) {

            $query->where(
                'category_id',
                $request->category
            );

        }


        $documents = $query->latest()->get();


        $categories = Category::orderBy('name')->get();


        return view(
            'documents.index',
            compact(
                'documents',
                'categories'
            )
        );
    }



    public function create()
    {
        $categories = Category::orderBy('name')->get();


        return view(
            'documents.create',
            compact('categories')
        );
    }



    public function store(
        Request $request,
        GoogleDriveService $google
    )
    {

        $request->validate([

            'document_number'
                => 'required|unique:documents,document_number',

            'title'
                => 'required',

            'category_id'
                => 'required|exists:categories,id',

            'file'
                => 'required|file'

        ]);



        $file = $request->file('file');



        $category = Category::findOrFail(
            $request->category_id
        );



        // Upload Google Drive sesuai kategori
        $uploaded = $google->upload(
            $file,
            $category->name
        );



        $filePath = $file->store(
            'documents',
            'public'
        );



        $document = Document::create([

            'document_number'
                => $request->document_number,

            'title'
                => $request->title,

            'category_id'
                => $request->category_id,

            'version'
                => $request->version ?? '1.0',

            'status'
                => 'aktif',

            'file_name'
                => $file->getClientOriginalName(),

            'file_path'
                => $filePath,

            'google_drive_id'
                => $uploaded->id,

            'google_file_name'
                => $uploaded->name,

            'file_type'
                => $file->getMimeType(),

            'file_size'
                => $file->getSize(),

            'user_id'
                => Auth::id(),

        ]);



        ActivityLog::create([

            'user_id'
                => Auth::id(),

            'document_id'
                => $document->id,

            'activity'
                => 'Upload Dokumen',

            'description'
                => 'Mengupload dokumen "' .
                   $document->title .
                   '" ke Google Drive',

        ]);



        return redirect()
            ->route('documents.index')
            ->with(
                'success',
                'Dokumen berhasil diupload ke Google Drive'
            );

    }

        public function show(string $id)
    {

        $document = Document::with([
            'category',
            'user'
        ])
        ->findOrFail($id);



        return view(
            'documents.show',
            compact('document')
        );

    }





    public function edit(string $id)
    {

        $document = Document::findOrFail($id);


        $categories = Category::orderBy('name')->get();



        return view(
            'documents.edit',
            compact(
                'document',
                'categories'
            )
        );

    }





    public function update(
        Request $request,
        string $id
    )
    {

        $document = Document::findOrFail($id);



        $request->validate([

            'document_number'
                => 'required|unique:documents,document_number,' . $id,

            'title'
                => 'required',

            'category_id'
                => 'required|exists:categories,id',

        ]);



        $document->update([

            'document_number'
                => $request->document_number,

            'title'
                => $request->title,

            'category_id'
                => $request->category_id,

        ]);



        ActivityLog::create([

            'user_id'
                => Auth::id(),

            'document_id'
                => $document->id,

            'activity'
                => 'Edit Dokumen',

            'description'
                => 'Mengubah dokumen "' .
                   $document->title .
                   '"',

        ]);



        return redirect()
            ->route('documents.index')
            ->with(
                'success',
                'Dokumen berhasil diperbarui'
            );

    }





    public function destroy(
        string $id,
        GoogleDriveService $google
    )
    {

        $document = Document::findOrFail($id);



        // Hapus dari Google Drive
        if ($document->google_drive_id) {

            $google->delete(
                $document->google_drive_id
            );

        }



        // Hapus file lokal
        if (
            $document->file_path &&
            Storage::disk('public')
                ->exists($document->file_path)
        ) {

            Storage::disk('public')
                ->delete($document->file_path);

        }



        ActivityLog::create([

            'user_id'
                => Auth::id(),

            'document_id'
                => $document->id,

            'activity'
                => 'Hapus Dokumen',

            'description'
                => 'Menghapus dokumen "' .
                   $document->title .
                   '"',

        ]);



        $document->delete();



        return redirect()
            ->route('documents.index')
            ->with(
                'success',
                'Dokumen berhasil dihapus'
            );

    }





    public function preview(string $id)
    {

        $document = Document::findOrFail($id);



        return response()->file(

            storage_path(
                'app/public/' .
                $document->file_path
            )

        );

    }





    public function download(string $id)
    {

        $document = Document::findOrFail($id);



        ActivityLog::create([

            'user_id'
                => Auth::id(),

            'document_id'
                => $document->id,

            'activity'
                => 'Download Dokumen',

            'description'
                => 'Download dokumen "' .
                   $document->title .
                   '"',

        ]);



        return response()->download(

            storage_path(
                'app/public/' .
                $document->file_path
            ),

            $document->file_name

        );

    }


}