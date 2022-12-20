<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public $timestamps = true;

    protected function formatText(string $text = null) : ?string
    {
        return $text ? ucfirst(trim($text)) : $text;
    }

    protected function formatDateTime(string $datetime = null) : ?string
    {
        return $datetime ? str_replace("T", " ", $datetime) : $datetime;
    }

    protected function formatDateTimeToTimesatemp(string $datetime = null) : ?int
    {
        return $datetime ? strtotime(str_replace("T", " ", $datetime)) : $datetime;
    }

    protected function removeImage(string $file): bool
    {
        $filename = __DIR__."/../../../storage/app/".$file;
        return file_exists($filename) && unlink($filename);
    }
}
