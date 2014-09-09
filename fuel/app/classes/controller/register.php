<?php

use helper\token;
use Fuel\Core\Controller_Rest;
use Fuel\Core\Input;
use Fuel\Core\Model;

class Controller_Register extends Controller_Rest
{
	public function get_user()
	{
						
		$tokens = Model_Token::find('all');
		$users = Model_Users::find('all');
		$user = Model_Users::find(1);
		return $this->response(array(
			'users'=> $users,
			'email' => Input::get('email'),
			'status'=> 'sucessful creation',
				'nguoidung'=>$users,
				'type[0]' => gettype($users[1]),
				'fn' => $users[1]->password,
				'user' => $user,
				'tokens' => $tokens
				
		),
		200
		);
	}
	
	public function post_user()
	{
		$users = Model_Users::find('all');
		$responses = array();
		$flag = true;
		$msg = 'empty';
		foreach ($users as $user)
		{
			if ($user->email == Input::post('email'))
			{
				$msg = 'This user is already exist';
				$flag = false;
			}			
		}
//		$flag = true; //for test
		if($flag)
		{
			$user_table = Model_Users::forge();
			$user_table->user_id = Input::post('user_id');
			$user_table->user_name = Input::post('user_name');
			$user_table->password = md5(Input::post('password'));
            $user_table->email = Input::post('email');
            $user_table->created = date('now');
			if(Input::post('repassword') != Input::post('password'))
			{	
				$msg = 're-password not match';
			}
			else 
			{
				if($user_table->save())
					$msg = 'Create user successfully';
			}
		}
		
		$responses['msg'] = $msg;
		$responses['id'] = Input::post('user_id');
// 		$responses['user'] = $user;
// 		$responses['instance'] = gettype($users);
		return $this->response($responses);	
	}

	public function action_404()
	{
		return Response::forge(Presenter::forge('welcome/404'), 404);
	}
}
