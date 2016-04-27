<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Validator;
use App\User as User;

class AuthController extends Controller
{
    public function register(Request $request){

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'password' => 'required|string|min:8',
            'email' => 'required|unique:users|email'
        ]);

        if(!empty($validator->errors()->all())){
            return response()->json(
                [
                    'code' => 400,
                    'message' => 'Validation failed.',
                    'data' => $validator->errors()->all()
                ],400);
        }

        $user = new User();
        $user->id = null;
        $user->name = strip_tags($request->get('name'));
        $user->password = md5($request->get('password'));
        $user->email = strip_tags(($request->get('email')));

        $user->token = md5(time().rand(0,100000)).'.'.base64_encode(json_encode(['user' => ['email' => $user->email]]));
        if(!$user->save()){
            return response()->json(
                [
                    'code' => 500,
                    'message' => 'Register error occured'
                ],500);
        }

        return response()->json($user->toArray(),200);

    }

    public function login(Request $request){

        $validator = Validator::make($request->all(), [
            'password' => 'required|string|min:8',
            'email' => 'required|email'
        ]);

        if(!empty($validator->errors()->all())){
            return response()->json(
                [
                    'code' => 400,
                    'message' => 'Validation failed.',
                    'data' => $validator->errors()->all()
                ],400);
        }

        $token = md5(time().rand(0,100000)).'.'.base64_encode(json_encode(['user' => ['email' => $request->get('email')]]));

        User::query()
            ->where('email', $request->get('email'))
            ->where('password', md5($request->get('password')))
            ->update(array('token' => $token));;

        $user = User::query()
            ->where('email', $request->get('email'))
            ->where('password', md5($request->get('password')))
            ->first();

        if(!$user){
            return response()->json(
                [
                    'code' => 401,
                    'message' => 'Authentication failed.',
                    'data' => 'User does not exist or password incorrect'
                ],401);
        }

        return response()->json($user->toArray(),200);
    }

    public function logout(Request $request){

        $token = $request->header('token');
        $userData = last(explode('.',$token));
        $data = json_decode(base64_decode($userData));
        try {
            $res = User::query()->where('email', $data->user->email)->where('token',
                $token)->update(array('token' => null));
        }catch(\Exception $e){
            return response()
                ->json(
                    [
                        'code' => 400,
                        'message' => 'Unauthorized.'],
                    401);
        }
        if(!$res){
            return response()
                ->json(
                    ['message' => 'Error logout.'],
                    400);
        }

        return response()
            ->json(
                ['message' => 'Successfully logged out.'],
                200);
    }
}
