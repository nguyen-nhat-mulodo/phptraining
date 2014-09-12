<?php
use \Model_mUser;
use \Model_mToken;
use \Model_mGift;


class Controller_GiftUser extends Controller_Rest
{

	public function action_Gift()
	{
	    //post parameter
	    $mail = Input::post('mail');
	    $token_id = Input::post('token_id');
	    // $image = Input::post('image');
	    $sender_id = '';
	    $reciever_id = '';
	    $image = '';
	    $thumb = '';
	    //check validation
	    if (($mail == "") || ($token_id == "")) {
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

	        if($data_token['expired'] <= $now)
	        {
	            $this->Response(
	                array(
	                        'status'  => 403,
	                        'message'   => "Session expired",
	                )
	            );
	            return;
	        }
	        $sender_id = $data_token['user_id']; 
	        
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
	        $reciever_id = $data_user['id'];
	        if ($data_user == 0) {
	            $this->Response(
	                    array(
	                            'status'    => 405,
	                            'message'   => "Not Exist User",
	                    )
	            );
	            return;
	        }
	        if ($sender_id == $reciever_id or $data_user['ban']==1) {
	            $this->Response(
	                    array(
	                            'status'    => 404,
	                            'message'   => "reciever_id is not correct (yourself or ban user)",
	                    )
	            );
	            return;
	        }
	        
	        
	    } catch (Exception $e) {
	        
	        $this->Response(
	                array(
	                        'status'    => 500,
	                        'message'   => "Internal Server Error",
	                )
	        );
	        return;
	    }
	 
        
        
        //upload image
        try {

	        
	         // Custom configuration for this upload
			$config = array(
			    'path' => DOCROOT.'files/upload',
			    'randomzise' => true,
			    'ext_whitelist' => array('img', 'jpg', 'jpeg', 'gif', 'png'),
			);

			// process the uploaded files in $_FILES
			Upload::process($config);

			// if there are any valid files
			if (Upload::is_valid())
			{
			    // save them according to the config
			    Upload::save();
			    $value = Upload::get_files();
			    $image = $value[0]['saved_as'];

			    // foreach ($file as $key => $value) {
			    $file = $value[0];
			    	// print_r($file);
			    	$filepath = $file['saved_to'].$file['saved_as'];
			    	$width = 640;
			    	$height = 480;
			    	$file_resize = $file['saved_to'].'resize/'.$image;
			    	Image::load($filepath)->resize($width,$height)->save($file_resize);
			    	$thumb = 'resize/'.$image;
			    // }
			    // $load = Image::load('./files/upload/'.$image);
			    // print_r($load);
			    // echo DOCROOT.'files/upload/'.$image;
			    	// echo $file['saved_to'].'resize/'.$image;die();
				// Image::load($filepath)->save($file['saved_to'].'resize/'.$image);
			    // print_r($value);echo $value[0]['name'];	die();
			    // call a model method to update the database
			    // Model_Uploads::add(Upload::get_files());
			}

			// and process any errors
			foreach (Upload::get_errors() as $file)
			{
			    // $file is an array with all file information,
			    // $file['errors'] contains an array of all error occurred
			    // each array element is an an array containing 'error' and 'message'
			}
			
	        
	    } catch (Exception $e) {
	        
	        $this->Response(
	                array(
	                        'status'    => 409,
	                        'message'   => "upload Error",
	                )
	        );
	        return;
	    }

	    //insert gift table
	    $m3 = new Model_mGift();
	    try {
	    	// if($mode ==0){
	    	// 	$content = "admin ".$admin_id." set nomal to user ".$user_id;
	    	// }else{
	    	// 	$content = "admin ".$admin_id." set admin to user ".$user_id;
	    	// }
	        
	        $data_gift = $m3->save_gift($sender_id,$reciever_id,$image,$thumb);
	        
	        
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
                            'message'   => "update Gift Successfull",
                    )
            );
            return;

	    
		
	}

}
