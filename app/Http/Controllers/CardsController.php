<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\User as User;

class CardsController extends Controller
{
    public function index(User $user){
        return response()->json(['test' => 'GET'], 200);
    }

    public function show(){
        return response()->json(['test' => 'GET+'], 200);
    }

    public function store(User $user){
        return response()->json(['test' => 'POST'], 200);
    }

    public function update(){
        return response()->json(['test' => 'PUT/PATCH'], 200);
    }

    public function destroy(){
        return response()->json(['test' => 'DELETE'], 200);
    }
}
