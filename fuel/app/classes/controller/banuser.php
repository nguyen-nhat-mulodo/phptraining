<?php
use \Model_mUser;
use \Model_mToken;
use \Model_mHistoryUser;


class Controller_BanUser extends Controller_Rest
{

	public function action_Ban()
	{
	    //post parameter
	    $mail = Input::post('mail');
	    $token_id = Input::post('token_id');
	    $mode = Input::post('mode');
	    $user_id = '';
	    $admin_id = '';
	    
	    //check validation
	    if (($mail == "") || ($token_id == "")||($mode == "")) {
	        $this->Response(
	                array(
	                        'status'  => 401,
	                        'message'   => "Invalid Argument",
	                )
	        );
	        return;
	    }
	    
	    //check token
	    $m2 = new Model_mToken();
	    try {

	        $data_token = $m2->search_token($token_id);
	        if ($data_token == 0) {
	            $this->Response(
	                    array(
	                            'status'    => 402,
	                            'message'   => "Not Exist Token",
	                    )
	            );
	            return;
	        }
	        $now = (new DateTime())->format('Y-m-d H:i:s');

	        if($data_token['expired'] < $now)
	        {
	            $this->Response(
	                array(
	                        'status'  => 403,
	                        'message'   => "Session expired",
	                )
	            );
	            return;
	        }
	        $admin_id = $data_token['user_id']; 
	        
	    } catch (Exception $e) {
	        
	        $this->Response(
	                array(
	                        'status'    => 500,
	                        'message'   => "Internal Server Error",
	                )
	        );
	        return;
	    }

	    $m = new Model_mUser();
	    //search user
	    try {

	        $data_user = $m->search_user($mail);
	        if ($data_user == 0) {
	            $this->Response(
	                    array(
	                            'status'    => 405,
	                            'message'   => "Not Exist User",
	                    )
	            );
	            return;
	        }	
	        $user_id = $data_user['id'];
	        // echo $user_id;        
	        
	    } catch (Exception $e) {
	        
	        $this->Response(
	                array(
	                        'status'    => 500,
	                        'message'   => "Internal Server Error",
	                )
	        );
	        return;
	    }

	    //update user
	    try {

	        $data_user = $m->ban_user($mail,$mode);        
	        
	    } catch (Exception $e) {
	        
	        $this->Response(
	                array(
	                        'status'    => 500,
	                        'message'   => "Internal Server Error",
	                )
	        );
	        return;
	    }

	    //insert history user
	    $m3 = new Model_mHistoryUser();
	    try {
	    	if($mode ==0){
	    		$content = "admin ".$admin_id." set unban to user ".$user_id;
	    	}else{
	    		$content = "admin ".$admin_id." set ban to user ".$user_id;
	    	}
	        $data_user = $m3->save_historyuser($user_id,'1',$content);
	        
	        
	    } catch (Exception $e) {
	        
	        $this->Response(
	                array(
	                        'status'    => 500,
	                        'message'   => "Internal Server Error",
	                )
	        );
	        return;
	    }
        

        $this->Response(
                    array(
                            'status'    => 200,
                            'message'   => "update ban Successfull",
                    )
            );
            return;

	    $id = $data_user["id"];
	    
	    $temp = new DateTime();
	    $dt = $temp->format('Y-m-d H:i:s');
	    
	    $token_id = hash_hmac('sha1',$dt,$id,false);
	    //var_dump($token_id);exit;
	    $m2 = new Model_mToken();
	    
	    $m2->save_token($token_id, $id);
	    
	    $this->Response(
	            array(
	                    'status'  => 200,
	                    'message' => "Login Successfull",
	            )
	    );
	    
		
	}

}
