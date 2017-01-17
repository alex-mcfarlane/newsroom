<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\UploadedFile;

class Image extends Model
{
    protected $fillable = ['path'];
    protected $baseDir = 'articles/images';

    public static function fromRequest(UploadedFile $file)
    {
    	$image = new static;

    	$name = time(). $file->getClientOriginalName();

	    $image->path = $image->baseDir. '/' . $name;

	    $file->move($image->baseDir, $name);

	    return $image;
    }
}
