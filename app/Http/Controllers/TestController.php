<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

class TestController extends Controller
{
	public function index(Request $request){
		return [
			'status' => 'ok', 
			'method' => 'GET',
			'message' => 'This for get list of all Models',
			'data' => $request->all()
		];
	}

	public function show(Request $request, $id){
                return [
                        'status' => 'ok',
                        'method' => 'GET',
                        'message' => 'This for get info about Model',
                        'data' => $request->all()
                ];
        }

	public function store(Request $request){
		return [
                        'status' => 'ok',
			'endpoint' => '/test',
                        'method' => 'POST',
			'message' => 'This for create Model',
                        'data' => $request->all()
                ];
	}

	public function destroy(Request $request, $id){
                return [
                        'status' => 'ok',
			'endpoint' => '/test/'.$id,
                        'method' => 'DELETE',
			'message' => 'This for delete one Model',
                        'data' => $request->all()
                ];
        }

	public function update(Request $request, $id){
		return [
                        'status' => 'ok',
			'endpoint' => '/test/'.$id,
                        'method' => 'PUT/PATCH',
			'message' => 'This for update one Model',
                        'data' => $request->all()
                ];
	}
}
