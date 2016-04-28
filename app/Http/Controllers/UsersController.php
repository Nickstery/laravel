<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\User as User;
use App\Http\Requests;
use Validator;

class UsersController extends Controller
{
    public function index(User $user){
        $info = $user->toArray();
        unset($info['token']);
        return $info;
    }

    public function store(User $user, Request $request){

        $changed = false;

        $validator = Validator::make($request->all(), [
            'name' => 'string|max:255',
            'password' => 'tring|min:8',
            'email' => 'unique:users|email'
        ]);

        if(!empty($validator->errors()->all())){
            return response()->json(
                [
                    'code' => 400,
                    'message' => 'Validation failed.',
                    'data' => $validator->errors()->all()
                ],400);
        }

        if($request->get('name')){
            $user->name = strip_tags($request->get('name'));
            $changed = true;
        }

        if($request->get('password')){
            $user->password = md5($request->get('password'));
            $changed = true;
        }

        if($changed){
            $user->save();
        }
        $info = $user->toArray();
        unset($info['token']);
        return $info;

    }
}
