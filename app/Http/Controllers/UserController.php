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

	//this is used in live version of the app on route /login to login the user
	public function skautISLogin(SkautIS $skautis, UserService $service){
		$user = $service->skautISLogin(Input::get('skautIS_Token'), Input::get('skautIS_IDRole'), Input::get('skautIS_IDUnit'),$skautis);
		Auth::login($user);
		return view('index_page');
	}

	//this is used in local testing on route /login to log in any user
	public function login($id){
		$user = User::find($id);
		Auth::login($user);
	}

	//this is used in local version on route \logout to logout the user
	public function logout(SkautIS $skautis){
		Auth::logout();
	}

	//this is used in live version on route \logout to logout the user on 
	public function skautISLogout(User $user,SkautIS $skautis){
		if(Auth::check()){
			$token_id = Auth::user()->token_id;
			Auth::logout();
			return redirect ('https://test-is.skaut.cz/Login/LogOut.aspx?appid=291fb631-97cf-4a2e-ad6b-1b3b14b9d9a2&token='.$token_id);
		}	
	}

	//this is used in both versions to get currently logged in user
	public function getCurrent(){
		if(Auth::check()){
			return Auth::user();
		}else{
		 	abort(404);
		}
	}


	public function getAll(UserService $service){
        $this->authorize('index',User::class);
		return $service->getAll();
	}

	public function getTemplates(User $user, UserService $service){
		return $service->getUserTemplates($user);
	}

	public function getTemplateInstances(User $user, UserService $service){
		return $service->getUserTemplateInstances($user);
	}

	public function show(User $user, UserService $service){
		return $service->getUserById($user->id);
	}
}
