<?php
use \Model_mUser;
use \Model_mToken;
use \Model_mHistoryUser;


class Controller_Roleuser extends Controller_Rest
{

	public function action_Role()
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

	    //search user
	    $m = new Model_mUser();
	    
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

	        $data_user = $m->role_user($mail,$mode);
	         // echo "aaa";die();
	        
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
	    		$content = "admin ".$admin_id." set nomal to user ".$user_id;
	    	}else{
	    		$content = "admin ".$admin_id." set admin to user ".$user_id;
	    	}
	        
	        $data_user = $m3->save_historyuser($user_id,$admin_id,$content);
	        
	        
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
                            'message'   => "update role Successfull",
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
