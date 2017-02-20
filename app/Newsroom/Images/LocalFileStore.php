<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Newsroom\Images;

use App\Newsroom\Interfaces\IFileStore;
use App\Image;

/**
 * Description of LocalFileStore
 *
 * @author AlexMc
 */
class LocalFileStore implements IFileStore
{
    public function store($uploadedFile, $baseDir, $fileName)
    {
        $uploadedFile->move($baseDir, $fileName);
    }
    
    public function delete($path)
    {
        if(! @unlink($path)) {
            return false;
        }
        
        return true;
    }
}
