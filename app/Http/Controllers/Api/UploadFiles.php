<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\CongressmanBudget;
use App\Data\Repositories\Files as FilesRepository;
use App\Http\Requests\UploadFile as UploadFileRequest;

class UploadFiles extends Controller
{
    public function index(Request $request)
    {
        return view('admin.upload_files.index')->with(
            'uploadedFiles',
            app(FilesRepository::class)
                ->model()
                ->all()
        );
    }

    public function create()
    {
        return view('admin.upload_files.form')->with([
            'uploadedFiles' => app(FilesRepository::class)->new(),
        ]);
    }

    public function store(UploadFileRequest $request)
    {
        app(FilesRepository::class)->uploadFile(
            $request->all(),
            CongressmanBudget::class
        );

        return redirect()
            ->route('upload-files.index')
            ->with($this->getSuccessMessage('Arquivo está sendo processado.'));
    }
}
