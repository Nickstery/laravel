<?php

namespace App\Helpers;
use Storage;

class ImageUploader
{
    public static function uploadImage($owner_id){
        Storage::disk('local')->put('file.txt', 'Contents: owner_id'.$owner_id);
    }
}