<?php
use helper\token;
use Fuel\Core\Controller_Rest;
use Fuel\Core\Input;
class Controller_Post extends Controller_Rest
{
	public function get_post()
	{
		$posts = Model_Posts::find('all');
		
		$msg = '';
		if($posts == null)
			$msg = 'no posts found ';
		return $this->response(array('posts' => $posts, 'msg'=>$msg));
	}

	public function post_post()
	{	
		$status = 200;
		$msg = ' ';

                $user_id = Input::post('user_id'); // It likes session_id represent for certain user.
		$token_id = Input::post('token_id');
                $token = token::check_update_exist_token($token_id, $user_id);
		$is_logged = $token === null? false:true;
		if($is_logged == true)
		{
			$content = Input::post('content');
			$title = Input::post('title');
			$user_id = Input::post('user_id');
			
			$post_table = Model_Posts::forge();			
			$post_table->title = $title;
			$post_table->content = $content;
			// 		$post_table->post_id = $post_id;
			$post_table->user_id = $user_id;
			$post_table->created = strtotime("now");
			$post_table->modified = date('now');
			$flag = true;
			
			if($user_id !== '' || $user_id !== null)
			{

					if($title === '' || $title === null)
					{
						$flag = false;
						$msg = 'you must enter the title ';
						$status = 402;
					}					
					if($content === '' || $content === null)
					{
						$flag = false;
						$msg = 'you must enter the content ';
						$status = 402;
					}
								
			}
			else
				$flag = false;
			
			if($flag)
			{
				if($post_table->save())
					$msg = 'Create post succesfully ';
				else
				{
					$msg = 'Database has a problem';
					$status = 500;
				}
			}
	
						
		}
		elseif ($is_logged == false)
		{
			$status = 402;
			$msg = 'You have not logged in';
		}
		
		return $this->response(array(
			'msg' => $msg,
			'status' => $status,			
			'timestamp' => strtotime('now')
		));
	}

}
