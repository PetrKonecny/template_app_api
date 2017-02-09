<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use SkautIS\SkautIS;
use Illuminate\Support\Facades\Input;
use App\Services\UserService;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests;
use App\User;

class UserController extends Controller
{
    
	public function show(SkautIS $skautis){
		$skautis->setLoginData(Input::get('skautIS_Token'), Input::get('skautIS_IDRole'), Input::get('skautIS_IDUnit'));
		$data = $skautis->user->LoginDetail();
		echo json_encode($data);
	}

	public function skautISLogin(SkautIS $skautis){
		$service = new UserService();
		$user = $service->processSkautISLogin(Input::get('skautIS_Token'), Input::get('skautIS_IDRole'), Input::get('skautIS_IDUnit'),$skautis);
		Auth::login($user);
		return view('index_page');
	}

	public function login($id){
		$user = User::find($id);
		Auth::login($user);
	}

	public function logout(){
		Auth::logout();
	}

	public function getCurrent(){
		return Auth::user();
	}
}