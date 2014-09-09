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

                $user_id = Input::post('user_id');
		$token_id = Input::post('token_id');
                $token = token::check_update_exist_token($token_id, $user_id);
		$is_logged = $token === null? false:true;
		$user = null;
                if ($is_logged == true)
		{
			$msg = 'You have already logged';
		}
		else
		{
			$msg = 'You haven\'t logged ';
			$email = Input::post('email'); 
			$pass = Input::post('password');
			if($email != null || $pass != null)
			{	
				$user = Model_Users::find('all', array(		
					'where' => array(
							array('email', $email),
							array('password', md5($pass)),
					)
				));
				$user = reset($user);
				if($user == null)
				{
					$msg = 'error login';
					$status = 402;
				}			
				else
				{
					$token = new token();
					$token->set_user_id($user->id);
					$token_id = $token->get_token_id();
					$token->save();
                                        $msg = 'login sucessful ';
				}		
			}	
		}

// 		$is_expire = token::is_expired($token->get_token_id())? 'true':'false';

		return $this->response(array(
				'msg'=>$msg,
				'token_id' => $token_id,
				'is_logged'=> $is_logged,
                                'typetoken'=> gettype($is_logged),
// 				'user_id' => $user->id,
// 				'email' => gettype($email),
// 				'user'=> var_dump($user),
// 				'date'=> date('d-m-Y h:i:s', $token->get_timestamp()),
// 				'is_expired' => $is_expire,
// 				'token' => $token,
// 				'date'=>$token->get_timestamp(),
				'user'=> $user,
// 				'pass'=> md5($pass),
// 				'query'=> \DB::last_query(),
			$status));
	}

}
