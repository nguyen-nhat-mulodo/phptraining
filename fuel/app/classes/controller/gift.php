<?php

use \Model_mUser;
use \Model_mToken;
use \Model_gift;
use Fuel\Core\Input;

class Controller_gift extends Controller_Rest
{
    protected  $format = 'json';
    
    
    public function action_sendgift()
    {
        //post parameter
        $user_id = Input::post('id_user');
        $token = Input::post('token');

        //check validation
        if (($user_id == "") || (empty($_FILES)) or ($token == "")) {
            $this->Response(
                array(
                        'status'  => 401,
                        'message'   => "Invalid Argument",
                )
            );
            return;
        }

        $mtoken = new Model_mToken();
        
        $data = array();
        
        //Check token
        try {
            $data_token = $mtoken->search_token($token);

        } catch (Exception $e) {
            $this->Response(
                    array(
                            'status'    => 500,
                            'message'   => "InternalÊServerÊError",
                    )
            );
            return;
        }
        if($data_token == 0)
        {
            $this->Response(
                array(
                        'status'  => 400,
                        'message'   => "Not exist token",
                )
            );
            return;
        }
        

        $now = (new DateTime())->format('Y-m-d H:i:s');

        if($data_token['expired'] < $now)
        {
            $this->Response(
                array(
                        'status'  => 407,
                        'message'   => "Session expired",
                )
            );
            return;
        }

        //Check User Account
        $muser = new Model_mUser();
        try {
            $data_user = $muser->search_user_id($user_id);
            

        } catch (Exception $e) {
            $this->Response(
                    array(
                            'status'    => 500,
                            'message'   => "InternalÊServerÊError",
                    )
            );
            return;
        }

        if($data_user == 0)
        {
            $this->Response(
                    array(
                            'status'    => 402,
                            'message'   => "Not exist User",
                    )
            );
            return;
        }
        
        if($user_id == $data_token['user_id'] || $data_user[0]['user_ban'] == 1)
        {
            $this->Response(
                    array(
                            'status'    => 501,
                            'message'   => "User was banned or same user send",
                    )
            );
            return;
        }

        $path = DOCROOT.'upload/images/image_gift/';
        $config = array(
            'path' => DOCROOT.'upload/images/image_gift/',
            'randomize' => true,
            'max_size' => '10000',
            'ext_whitelist' => array('img', 'jpg','jpeg', 'gif', 'png'),
        );
        
        Upload::process($config);
        
        if (Upload::is_valid())
        {
            
            
            // save them according to the config
            Upload::save();
            
           //if you want to save to tha database lets grab the file name
            $value = Upload::get_files();
            $image_name = $value[0]['saved_as'];
            $thumb_name = 'thumb'.$value[0]['saved_as'];
            Image::load($path.$image_name)
                ->crop_resize(200, 200)
                ->save($path.'thumb/'.$thumb_name);

            $mgift = new Model_gift();
            

            //Send gift
            try {
                $insert_id_gift = $mgift->add_gift($data_token['user_id'],$user_id,$image_name,$thumb_name);
                 

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
        else
        {

            $this->Response(
                array(
                        'status'  => 409,
                        'message'   => "Error upload file",
                )
            );
            return;
        }

        
        $this->Response(
                    array(
                            'status'    => 200,
                            'message'   => "Successful",
                    )
            );
            return;
        

    }
    //Add Date: 19/09/2014 Name: TranQuocDung End
}