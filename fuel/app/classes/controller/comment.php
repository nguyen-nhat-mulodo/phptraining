<?php
use helper\token;
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

        $user_id = Input::post('user_id'); // It likes session_id represent for certain user.
		$token_id = Input::post('token_id');
        $token = token::check_update_exist_token($token_id, $user_id);
		$is_logged = $token === null? false:true;
		
		if($is_logged == true)
		{
			$post_id = Input::post('post_id');
			$content = Input::post('content');
			$user_id = Input::post('user_id');
			// 		$title = Input::post('title');
			
			$comment_table = Model_Comments::forge();
			$comment_table->post_id = $post_id;
			$comment_table->content = $content;
			$comment_table->user_id = $user_id;
			$comment_table->created = date('now');
			$comment_table->modified = date('now');
			$flag = true;
			
			if($user_id !== '' || $user_id !== null)
			{
				if($post_id !== '' || $post_id !== null )
				{	
					if($content === '' || $content === null)
					{
						$flag = false;
						$msg = 'you have to enter the content ';
						$status = 402;
					}		
				}		
				if($post_id === null || $post_id === '')
				{
					$flag = false;
					$msg = 'the post id not exist';
				}
					
			}
			else 
				$flag = false;
			
			if($flag)
			{
				if($comment_table->save())
					$msg = 'Create comment succesfully ';
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
			$msg = 'You have not logged in ';
		}


		return $this->response(array(
			'msg' => $msg,			
			'status' => $status,	
// 				'post_id_type' => gettype($post_id),
// 				'post_id'=> $post_id,
		));
	}

}
