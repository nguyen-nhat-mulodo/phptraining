<?php
use helper\token;
use Fuel\Core\Controller_Rest;
use Fuel\Core\Input;
class Controller_Login extends Controller_Rest
{

	public function post_authen()
	{
		$status = 200;
		$msg = ' ';

		$token_id = Input::post('token_id');
		$is_logged = token::check_update_exist_token($token_id);
		if($is_logged == false)
		{
			$email = Input::post('email'); 
			$pass = Input::post('password');
			$user = Model_Users::find('all', array(		
				'where' => array(
						array('email', $email),
						array('password', md5($pass)),
				)
			));
			$user = reset($user);
			if($user === null)
			{
				$msg = 'This user not found ';
				$status = 402;
			}			
			else
			{
				$token = new token();
				$token->set_user_id($user->id);
				$token_id = $token->get_token_id();
				$token->save();
			}			
		}
		elseif ($is_logged == true)
		{
			$msg = 'You have already logged';
		}
		$is_expire = token::is_expired($token->get_token_id())? 'true':'false';

		return $this->response(array(
				'msg'=>$msg,
				'token_id' => $token_id,
// 				'user'=> var_dump($user),
// 				'date'=> date('d-m-Y h:i:s', $token->get_timestamp()),
				'is_expired' => $is_expire,
// 				'token' => $token,
// 				'date'=>$token->get_timestamp(),
				'user'=> $user,
// 				'query'=> \DB::last_query(),
			$status));
	}

}
