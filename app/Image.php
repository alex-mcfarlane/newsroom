<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\UploadedFile;
use App\Newsroom\Interfaces\IFileStore;

class Image extends Model
{
    protected $fillable = ['path'];
    protected $baseDir = 'articles/images';

    public static function fromRequest(UploadedFile $file, IFileStore $fileStore)
    {
    	$image = new static;

    	$name = time(). $file->getClientOriginalName();

	    $image->path = $image->baseDir. '/' . $name;

        $fileStore->store($file, $image->path, $name);
	    //$file->move($image->baseDir, $name);

	    return $image;
    }

    public static function defaultImage()
    {
    	$image = new static;
    	$image->path = $image->baseDir. '/default.jpg';

    	return $image;
    }
}
