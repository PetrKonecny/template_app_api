<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use SkautIS\SkautIS;
use Illuminate\Support\Facades\Input;
use App\Services\UserService;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests;
use App\User;
use App\Template;

class UserController extends Controller
{
    /*
	public function show(SkautIS $skautis){
		$skautis->setLoginData(Input::get('skautIS_Token'), Input::get('skautIS_IDRole'), Input::get('skautIS_IDUnit'));
		$data = $skautis->user->LoginDetail();
		echo json_encode($data);
	}*/

	public function skautISLogin(SkautIS $skautis){
		$service = new UserService();
		$user = $service->skautISLogin(Input::get('skautIS_Token'), Input::get('skautIS_IDRole'), Input::get('skautIS_IDUnit'),$skautis);
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

	public function getAll(UserService $service){
		return $service->getAll();
	}

	public function getTemplates(User $user, UserService $service){
		return $service->getUserTemplates($user);
	}

	public function getTemplateInstances(User $user, UserService $service){
		return $service->getUserTemplateInstances($user);
	}

	public function show(User $user){
		return $user;
	}
}
