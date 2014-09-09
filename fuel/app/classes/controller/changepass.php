<?php
use helper\token;
use Fuel\Core\Controller_Rest;
use Fuel\Core\Input;
class Controller_Changepass extends Controller_Rest
{

	public function post_change()
	{
		$msg = ' ';
		$status = 200;
                $user_id = Input::post('user_id'); // It likes session_id represent for certain user.
		$token_id = Input::post('token_id');
                $token = token::check_update_exist_token($token_id, $user_id);
		$is_logged = $token === null? false:true;
                if ($is_logged == true)
		{			
			$user = Model_Users::find($user_id);
			
			$oldpass = md5(Input::post('oldpassword'));
			$newpass = Input::post('newpassword');
			$repass = Input::post('repassword');
			$userpass = $user->password;			
			
			if($user != null)
			{
				if($oldpass == $userpass)			
					if($repass == $newpass)
					{
						$user->password = md5($newpass);
						if($user->save())
							$msg = 'update sucessfully ';
					}			
				if($oldpass != $userpass)
					$msg = 'old password is not correct ';			
			}
			elseif ($user == null)
			{
				$msg = 'this user not exist ';
				$status = 402; 
			}
// 			$msg = 'da log';

		}	
		elseif ($is_logged == false)
		{
			$msg = 'you have not logged in ';
		}
		return $this->response(array(
// 				'user' => $user, 
// 				'typert'=>gettype($user), 
// 				'userpass' => $userpass,
				'msg'=>$msg,
				'status' => $status,
				'query'=> \DB::last_query(),
		));
		
	}

}
