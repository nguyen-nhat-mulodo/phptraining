<?php

use Fuel\Core\Controller_Rest;
use Fuel\Core\Input;
class Controller_Post extends Controller_Rest
{
	public function get_posts()
	{
		$posts = Model_Posts::find('all');
		
		$msg = '';
		if($posts == null)
			$msg = 'no posts found ';
		return $this->response(array('posts' => $posts, 'msg'=>$msg));
	}

	public function post_posts()
	{
		$status = 200;
		$msg = ' ';

		$token_id = Input::post('token_id');
		$is_logged = token::check_update_exist_token($token_id);
		
		$content = Input::post('content');
// 		$post_id = Input::post('post_id');
		$title = Input::post('title');
		$user_id = Input::post('user_id');
		
		$post_table = Model_Posts::forge();
		$msg = ' ';
		
		$post_table->title = $title;
		$post_table->content = $content;
// 		$post_table->post_id = $post_id;
		$post_table->user_id = $user_id;
		$post_table->created_at = strtotime("now");
		$flag = true;
		
		if($post_id !== '')        
		{
			if($title === '')      
			{
				$flag = false;
				$msg = 'you have to enter the title ';		
				$status = "402"; 			
			}		
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
				$msg = 'Create post succesfully ';
			else 
			{
				$msg = 'Database has a problem';
				$status = "500";
			}
		}

		return $this->response(array(
			'msg' => $msg,
			'status' => $status,			
			'timestamp' => strtotime('now')
		));
	}

}
