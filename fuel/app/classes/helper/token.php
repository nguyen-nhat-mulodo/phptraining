<?php
namespace helper;
use Model_Token;
class token 
{
	private $token_id;
	private $expired_timestamp = 0;
	private $user_id;
	
	public function __construct($token_id = '', $user_id = '', $n_hours = 2)
	{
		if($token_id === '' || $token_id === null)
			$this->token_id = hash_hmac('sha256', time(), uniqid(), false);
		else 
			$this->token_id = $token_id;
		
		if($n_hours !== '')
			$this->set_hour_expire($n_hours);
		if ($user_id !== '')
			$this->user_id = $user_id;
	}
// 	public function set_token_id()
// 	{
// 		$this->token_id = hash_hmac('sha3', time(), uniqid(), false);
// 	}
	
	public function set_user_id($user_id)
	{
		$this->user_id = $user_id;
	}
	public function get_user_id($user_id)
	{
		return $this->user_id = $user_id;
	}
	public function get_token_id()
	{
		return $this->token_id;
	}
	public function get_timestamp()
	{
		return $this->expired_timestamp;
	}
	public function set_hour_expire($n)
	{
		$this->expired_timestamp = strtotime("+$n hours");
// 		return $this;
	}
	public function set_day_expire($n)
	{
		$this->expired_timestamp = strtotime("+$n days");
// 		return $this;
	}
	static public function is_expired($token_id)
	{
		$token_rec = Model_Token::find('all', array(
				'where'=> array(
						array('token_id',$token_id),
				),
		));
		$token_rec = reset($token_rec);

		$timestamp = time();					
		if($token_rec->expired < $timestamp) 	
			return true;
		return false;
	}
	static public function remove_expired_token($token_id)
	{
		if(token::is_expired($token_id))
		{
			$token_rec = Model_Token::find('all', array(
					'where'=> array(
							array('token_id',$token_id),
					),
			));
			$token_rec = reset($token_rec);
			if($token_rec->delete())
				return true;
			return false;
		}
		return false;
			
	}
	public function save()
	{
		$token_table = Model_Token::forge();
		$token_table->token_id = $this->token_id;
		$token_table->expired = $this->expired_timestamp;
		$token_table->user_id = $this->user_id;
		$token_table->save();
	}
	public function update()
	{
		$token = Model_Token::find('all',array(
			'where' => array(
				array('token_id', $this->token_id),
			),
		));
		$token = reset($token);
		$token->expired = $this->expired_timestamp;
		$token->user_id = $this->user_id;
		$token->save();		
	}
	static public function get_exist_token($token_id)
	{
		$token_rec = Model_Token::find('all', array(
			'where'=> array(
				array('token_id',$token_id),
			),
		));
		$token_rec = reset($token_rec);
		if($token_rec != null)
		{
			$new_token = new token($token_rec->token_id, $token_rec->user_id);
			return  $new_token;
		}
			
		return null;
	}
	static public function check_update_exist_token($token_id, $hour_expire = 2)
	{
		$token = token::get_exist_token($token_id);
		if($token != null)
		{
			if(token::remove_expired_token($token->get_token_id()) === false) // token's still not expired
			{
				$token->set_hour_expire($hour_expire);						// then add more time expired
				$token->update();
				return true;
			}
			return false;
		}
		return false;
	}
	
	

}