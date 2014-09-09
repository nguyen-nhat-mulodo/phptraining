<?php

use \Model_mToken;
use \Model_mPost;

class Controller_CreatePost extends Controller_Rest{
    public function action_Create(){
        
        //post parameter
        $token = Input::post('token');
        $title = Input::post('title');
        $content = Input::post('content');
        
        //check validation
        if (($token == "") || ($title == "") || ($content == "")) {
            $this->Response(
                    array(
                            'status'  => 401,
                            'message'   => "Invalid Argument",
                    )
            );
            return;
        }
        
        try {
            $m = new Model_mToken();
            
            //Check user by token
            $data_user = $m->search_token($token);
             
            if ($data_user == 0) {
                $this->Response(
                        array(
                                'status'    => 402,
                                'message'   => "Not Exist User",
                        )
                );
                return;
            }
            
            $userid = $data_user["id"];
            
            //var_dump($userid);exit;

            $m2 = new Model_mPost();
            //Up a post         
            $insert_id = $m2->create_post($userid, $title, $content);
            
            $this->Response(
                    array(
                            'status'  => 200,
                            'message'   => "create post id " .$insert_id . " successful",
                    )
            );
            
        } catch (Exception $e) {
            $this->Response(
                    array(
                            'status'    => 500,
                            'message'   => "InternalÊServerÊError",
                    )
            );
            return;
        }
    }
}