<?php

namespace App\Data\Repositories;

use App\Models\AttachedFile;
use App\Models\File as FileModel;
use App\Models\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Files extends Repository
{
    protected $model = FileModel::class;

    /**
     * @param $model
     * @param $file
     * @param $originalName
     * @return \App\Models\AttachedFile
     */
    public function createAttachment($model, $file, $originalName): AttachedFile
    {
        return app(AttachedFiles::class)->firstOrCreate(
            [
                'file_id' => $file->id,
                'fileable_id' => $model->id,
                'fileable_type' => get_class($model),
            ],

            [
                'original_name' => $originalName,
            ]
        );
    }

    /**
     * @param $file
     * @param $uploadedFile
     * @return bool
     */
    protected function fileExists($file, $uploadedFile): bool
    {
        return Storage::disk($this->getDrive())->exists(
            $this->makePath($file->hash, $uploadedFile)
        );
    }

    private function findOrCreateFile($uploadedFile)
    {
        $hash = sha1(file_get_contents($uploadedFile->getPathName()));

        if (!($file = $this->findByHash($hash))) {
            $file = $this->new();

            $file->hash = $hash;

            $file->drive = $this->getDrive();

            $file->path = $this->makePath($hash, $uploadedFile);

            $file->mime_type = $uploadedFile->getClientMimeType();

            $file->save();
        }

        return $file;
    }

    private function makeDirectory($hash)
    {
        return make_deep_path($hash);
    }

    /**
     * @param $file
     * @param $uploadedFile
     */
    protected function storeFile($file, $uploadedFile): void
    {
        if (!$this->fileExists($file, $uploadedFile)) {
            $uploadedFile->storeAs(
                $this->makeDirectory($file->hash),
                $this->makeFileName($file->hash, $uploadedFile),
                $this->getDrive()
            );
        }
    }

    /**
     * @param $uploadedFile
     * @return File
     */
    public function storePhysicalFile($uploadedFile): File
    {
        $this->storeFile($file = $this->findOrCreateFile($uploadedFile), $uploadedFile);

        return $file;
    }

    private function getDrive()
    {
        return config('filesystems.documents_default', 'documents');
    }

    /**
     * @param $hash
     * @param $uploadedFile
     * @return string
     */
    private function makeFileName($hash, $uploadedFile): string
    {
        return $hash . '.' . Str::lower($uploadedFile->getClientOriginalExtension());
    }

    /**
     * @param $hash
     * @param $uploadedFile
     * @return string
     */
    private function makePath($hash, $uploadedFile): string
    {
        $deep = $this->makeDirectory($hash);

        $filename = $this->makeFileName($hash, $uploadedFile);

        return "{$deep}{$filename}";
    }
}
