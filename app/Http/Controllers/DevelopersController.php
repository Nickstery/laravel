<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

class DevelopersController extends Controller
{
    	public function index(Request $request){

		$developers = \App\Developers::all();
		if(!$developers){
			return [
                                'status' => 'error',
                                'message' => 'Users not found'
                                ];
		}

		return [
			'status' => 'ok',
			'items' => $developers
			];
	}

	public function show(Request $request, $id){

		$developer = \App\Developers::find($id);

		if(!$developer){
			return ['status' => 'error', 'message' => 'User with id='.$id.' not found'];
		}
		return [
			'status' => 'ok',
			'data' => $developer
			];
	}

	public function store(Request $request){

		if(empty($request->get('name'))){
			return [
				'status' => 'error', 
				'message' => 'Parameter "name" can not be empty'
				];
		}

		if(empty($request->get('position'))){
                        return [
                                'status' => 'error',
                                'message' => 'Parameter "position" can not be empty'
                                ];
                }

		$developer = new \App\Developers();
		$developer->name = $request->get('name');
		$developer->position = $request->get('position');
		$developer->save();

		return $developer->toArray();
	}

	public function destroy(Request $request, $id){
		$developer = \App\Developers::find($id);
		if(empty($developer)){
			return [
				'status' => 'error',
				'message' => 'Developer with id='.$id.' not found'
				];
		}
		$developer->delete();
		return [
			'status' => 'ok',
			'message' => 'Developer removed successfully'
			];
	}

	public function update(Request $request, $id){
		if(empty($request->get('name')) && empty($request->get('position'))){
			return [
				'status' => 'error', 
				'message' => 'Must be updated one or two parameters ["name", "position"]'
				];
		}
		$developer = \App\Developers::find($id);
		if(!empty($request->get('name'))){
			$developer->name = $request->get('name');
		}

		if(!empty($request->get('position'))){
                        $developer->position = $request->get('position');
                }

		$developer->save();
		return $developer->toArray();

	}
}
