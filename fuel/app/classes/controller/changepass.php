<?php

use Fuel\Core\Controller_Rest;
use Fuel\Core\Input;
class Controller_Changepass extends Controller_Rest
{

	public function post_change()
	{
		$id = Input::post('id'); // It likes session_id represent for certain user.
		$user = Model_Users::find($id);
		$oldpass = Input::post('oldpass');
		$newpass = Input::post('newpass');
		$repass = Input::post('repass');
		$userpass = $user->password;
		$msg = ' ';
		if($user != null)
		if($oldpass == $userpass)
		if($repass == $newpass)
		{
			$user->password = $newpass;
			if($user->save())
				$msg .= 'update sucessfully ';
		}
		
		if($oldpass != $userpass)
			$msg .= 'old password is not correct ';
		if($user == null)
			$msg .= 'user null ';

		return $this->response(array('user' => $user, 'typert'=>gettype($user), 'msg'=>$msg));
		
	}

}
