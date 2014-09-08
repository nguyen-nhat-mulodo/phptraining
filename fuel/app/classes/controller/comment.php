<?php

use Fuel\Core\Controller_Rest;
use Fuel\Core\Input;
class Controller_Comment extends Controller_Rest
{
	public function get_comment()
	{
		$post_id = Input::post('post_id');
		$posts = Model_Comments::find('all', array(
			'where'=> array(
				array('post_id',$post_id),
			)
		));
		
		$msg = '';
		if($posts == null)
			$msg = 'no post found ';
		return $this->response(array('posts' => $posts, 'msg'=>$msg));
	}

	public function post_comment()
	{
		$status = 200;
		$msg = ' ';

		$token_id = Input::post('token_id');
		$is_logged = token::check_update_exist_token($token_id);
		
		if($is_logged == false)
		{
			$post_id = Input::post('post_id');
			$content = Input::post('content');
			$user_id = Input::post('user_id');
			// 		$title = Input::post('title');
			
			$comment_table = Model_Comments::forge();
			$msg = ' ';
			
			// 		$post_table->title = $title;
			$comment_table->content = $content;
			$comment_table->user_id = $user_id;
			$comment_table->created_at = strtotime("now");
			$flag = true;
			
			if($user_id !== '')
			{
				if($content === '')
				{
					$flag = false;
					$msg = 'you have to enter the content ';
					$status = "402";
				}
			}
			
			if($flag)
			{
				if($post_table->save())
					$msg = 'Create comment succesfully ';
				else
				{
					$msg = 'Database has a problem';
					$status = "500";
				}
			}		
		}
		elseif ($is_logged)
		{
			$status = 402;
			$msg = 'You haven\'t logged yet';
		}


		return $this->response(array(
			'msg' => $msg,			
			'status' => $status,	
		));
	}

}
