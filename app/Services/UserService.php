<?php
namespace App\Services;

use App\Image;
use App\User;
use Illuminate\Support\Facades\Storage;
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
class UserService {
  
    public function getUserBySkautISToken($token_id){
        return $user = User::where('token_id',$token_id)->first();          
    }

    public function getSkautISLoginData($token_id, $group_id, $role_id, $skautis){
    	$skautis->setLoginData($token_id, $role_id, $group_id);
		return $skautis->user->LoginDetail(); 
    }

    public function isUserTokenExpired($token_id){
    	$user = $this->getUserBySkautISToken($token_id);
    	if($user == null) {	
    		return true;}
    	else if(time() - $user->token_refreshed_in  > 60*30){
    		return true;
    	}else{
    		return false;
    	}
    }

    public function refreshUserToken($token_id){
    	if($this->isUserTokenExpired()){

    	}
    }


    public function getUserById($id){
    	return User::find($id);
    }

    public function createUser($array){
    	$user = new User($array);
    	$user->save();
    	return $user;
    }

    public function skautISLogin($token_id, $role_id, $group_id, $skautisInst){
    	$user = $this->getUserBySkautISToken($token_id);
    	$data;
    	if($user == null){
    		$data = $this->getSkautISLoginData($token_id,$group_id,$role_id,$skautisInst);
    		$user = $this->getUserById($data->ID_User);
    	}
    	if($user == null && $data != null){
    		$user = $this->createUser([]);
    		$user->id = $data->ID_User;
    	}
    	$user->token_id = $token_id;
    	$user->save();
    	return $user;
    }

    public function updateUser($user,$array){
    	
    }
}

