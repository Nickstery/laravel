<?php
use File;
Route::get('/', function () {
    return view('welcome');
});


Route::get('v1/image/{owner_id}/{filename}', function($owner_id, $filename, \Illuminate\Http\Request $request){
    $path = storage_path() . '/user_uploads/'.$owner_id."/". $filename;
    if(!File::exists($path)) abort(404);
    $file = File::get($path);
    $type = File::mimeType($path);
    $response = Response::make($file, 200);
    $response->header("Content-Type", $type);
    return $response;
});


Route::post('auth/register', 'AuthController@register');
Route::post('auth/login', 'AuthController@login');
Route::any('logout', 'AuthController@logout');

Route::group(['prefix' => 'v1', 'middleware' => ['auth']],function(){


    Route::post('image', function(\Illuminate\Http\Request $request, App\User $user){
        $img = $request->file('image');
        if(empty($img)){
            return response()->json(['status' => 'error', 'message' => 'no image found'], 404);
        }
        $res = \App\Helpers\ImageUploader::uploadProfileImage($user->id, $img);
        if(!$res){
            return response()->json(['status' => 'error', 'message' => 'Image extention is incorrect, must be one of: PNG or JPG'], 404);
        }
        return response()->json(['status' => 'ok', 'message' => 'All is ok SANYOK!', 'data' => ['image_url' => env('APP_URL')."/v1/image/".$user->id."/".$res]]);
    });

    Route::resource('user', 'UsersController');
    Route::resource('cards', 'CardsController');
});
