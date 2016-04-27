<?php

namespace App\Http\Controllers;

use App\Cards;
use Illuminate\Http\Request;
use Validator;
use App\Http\Requests;
use App\User as User;

class CardsController extends Controller
{
    public function index(User $user){
        $cards = Cards::query()->where('owner_id', '=', $user->id)->get();

        return response()->json(['items' => $cards, 'count' => sizeof($cards)]);
    }

    public function show($id){
        $card = Cards::query()->where('id', '=', $id)->first();
        if(!$card){
            return response()->json(['code' => '404', 'message' => 'Card not found'], 404);
        }
        return response()->json($card->toArray(), 200);
    }

    public function store(User $user, Request $request){
        //Todo make validators for other inputs
        $validator = Validator::make($request->all(), [
            'company' => 'required|string|max:255',
            'about' => 'required|string|min:8'
        ]);

        if(!empty($validator->errors()->all())){
            return response()->json(
                [
                    'code' => 400,
                    'message' => 'Validation failed.',
                    'data' => $validator->errors()->all()
                ],400);
        }

        $card = new Cards();
        $card->id = null;
        $card->owner_id = $user->id;
        $card->company = strip_tags($request->get('company'));
        $card->about = strip_tags($request->get('about'));
        $card->save();
        $card->owner_name = $user->name;

        return response()->json($card->toArray(), 200);
    }

    public function update(){
        return response()->json(['test' => 'PUT/PATCH'], 200);
    }

    public function destroy($id, User $user){
        $card = Cards::query()->where('id', '=', $id)->where('owner_id', '=', $user->id)->first();
        if(!$card){
            return response()->json(['code' => 404, 'message' => 'Card with id=' . $id . ' not found'], 404);
        }
        $card->delete();
        return response()->json(['message' => 'Successfully removed'], 200);
    }
}
