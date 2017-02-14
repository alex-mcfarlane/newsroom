<?php

namespace App\Newsroom\Interfaces;

use Illuminate\Http\UploadedFile;

/**
 * Interface for FileStore objects
 *
 * @author Alex McFarlane
 */
interface IFileStore 
{
    public function store($uploadedFile, $directory, $fileName);
    
    public function delete($path);
}
