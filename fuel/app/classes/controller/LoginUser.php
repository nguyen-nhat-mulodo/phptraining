<?php
use \Model_mUser;
use \Model_mToken;

//kien test
class Controller_LoginUser extends Controller_Rest
{

	public function action_Login()
	{
	    //post parameter
	    $mail = Input::post('mail');
	    $password = Input::post('password');
	    
	    //check validation
	    if (($mail == "") || ($password == "")) {
	        $this->Response(
	                array(
	                        'status'  => 401,
	                        'message'   => "Invalid Argument",
	                )
	        );
	        return;
	    }
	    
	    $m = new Model_mUser();
	    
	    try {
	        $data_user = $m->search_login($mail, $password);
	        
	        if ($data_user == 0) {
	            $this->Response(
	                    array(
	                            'status'    => 402,
	                            'message'   => "Not Exist User",
	                    )
	            );
	            return;
	        }
	        
	        
	    } catch (Exception $e) {
	        
	        $this->Response(
	                array(
	                        'status'    => 500,
	                        'message'   => "InternalÊServerÊError",
	                )
	        );
	        return;
	    }
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
