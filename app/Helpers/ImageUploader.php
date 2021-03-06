<?php

namespace App\Helpers;
use Storage;
use App\Images as Images;

class ImageUploader
{
    public static function uploadProfileImage($owner_id = 123, $imgObj){

        if(!self::_checkMimeType($imgObj->getMimeType())){
            return false;
        }

        $filename  = "profile.".$imgObj->getClientOriginalExtension();

        if (!file_exists(storage_path() . '/user_uploads/' . $owner_id . "/")) {
            mkdir(storage_path() . '/user_uploads/' . $owner_id . "/");
        }

        $path = storage_path('user_uploads/'.$owner_id."/" . $filename);
        \Image::make($imgObj->getRealPath())->save($path);
        //ONLY ONE PIC!!!
        $userImg = Images::query()->where('owner_id', '=', $owner_id)->first();
        if(empty($userImg)){
            $userImg = new Images();
        }
        $userImg->image_name = $filename;
        $userImg->owner_id = $owner_id;
        $userImg->touch(); // to override the updated_at timestamp
        $userImg->save();
        return $userImg;
    }

    private static function _checkMimeType($mimeType){
        return in_array($mimeType, ['image/jpeg','image/pjpeg','image/png']);
    }
}