<?php

namespace App\Http\Services;

class FileServices
{
    public function upload($file, $path)
    {
        $upload = $file;
        $fileName = $upload->hashName();

        return $upload->storeAs($path, $fileName, 'public');
    }

    public function delete($path)
    {
        $deleted = unlink(storage_path('app/public/'.$path));
        return $deleted;
    }
}