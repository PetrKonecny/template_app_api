<?php
namespace App\Services;

use App\Image;
use App\User;
use App\Template;
use App\TemplateInstance;
use Illuminate\Support\Facades\Storage;

/**
 * Service providing database access for User model and SkautIS API
 */
class UserService {
  
    /**
    * gets user form skautIS API if token id is available
    * @param token_id - token id to communicate with API
    */
    public function getUserBySkautISToken($token_id){
        return $user = User::where('token_id',$token_id)->first();          
    }

    /**
    * gets skautIS login deta with user_id
    * @param token_id - token id to communicate with API
    * @param group_id - id of group from skautIS
    * @param role_id - role id from skautIS
    * @param skautis - instance of skautIS library 
    */
    public function getSkautISLoginData($token_id, $group_id, $role_id, $skautis){
    	$skautis->setLoginData($token_id, $role_id, $group_id);
		return $skautis->user->LoginDetail(); 
    }

    /** 
    * checks if token is expired
    * @param token_id - token id to communicate with API
    */
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

    //refreshes the token over API
    public function refreshUserToken($token_id){
    	if($this->isUserTokenExpired()){

    	}
    }

    /**
    * gets user model by id
    * @param id - id to seach by
    * @return user or null if none found
    */
    public function getUserById($id){
    	return User::find($id);
    }

    /**
    * creates user from the array
    * @param array - array of data to create user from
    * @return user - created user model
    */
    public function createUser($array){
    	$user = new User($array);
    	$user->save();
    	return $user;
    }

    public function getUserTemplates($user){
        return Template::where('user_id', $user->id)->get();
    }

    public function getUserTemplateInstances($user){
        return TemplateInstance::where('user_id',$user->id)->get();
    }

    /**
    *  gets all users from DB
    * @return all users
    */
    public function getAll(){
        return User::all();
    }

    /**
    *  processes login response from the API, if no user model exists it creates one
    * if propper role id returned it creates admin role
    */
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
        if($role_id === 4969){
            $user->admin = true;
        }else{
            $user->admin = false;
        }
    	$user->token_id = $token_id;
    	$user->save();
    	return $user;
    }

    public function skautISLogout($skautis){
    }

    public function updateUser($user,$array){
    	
    }
}

