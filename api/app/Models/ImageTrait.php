<?php

namespace App\Models;
use \Illuminate\Http\UploadedFile;
use \Illuminate\Support\Facades\File;
use App\Models\Image;

trait ImageTrait
{
    public function hasImage(string $filename = null) : bool
    {
        if($filename) return file_exists($this->formatFileName($filename));
        return false;
    }

    public function moveImage(UploadedFile $file = null) : ?string
    {
        return $file ? $file->store(Image::UPLOADS_PATH) : null;
    }

    public function removeImage(string $filename) : bool
    {
        if($this->hasImage($filename)) return unlink($this->formatFileName($filename));
        return false;
    }

    public function copyImage(string $source = null) : ?string
    {
        if ($this->hasImage($source)) {
            $timestemp = strtotime('now');
            $destination = explode(".", $source)[0] . $timestemp . explode(".", $source)[1];
            return File::copy($source, $destination) ? $destination : null;
        }
        return null;
    }

    public function formatFileName(string $filename, $publicPath = true) : string
    {
        $newFilename = str_replace(Image::UPLOADS_PATH, 'storage', $filename);
        if ($publicPath) return public_path($newFilename);
        return $newFilename;
    }

    public static function pathToUploadedFile(string $path) : ?UploadedFile
    {
        $name = File::name($path);
        $extension = File::extension($path);
        $filename = "{$name}.{$extension}";
        return new UploadedFile(
            $path, 
            $filename, 
            File::mimeType($path), 
            File::size($path),
        );
    }
}