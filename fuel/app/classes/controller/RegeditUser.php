<?php

use \Model_mUser;
use \Model_mToken;
use \Model_historyuser;
use Fuel\Core\Input;

class Controller_RegeditUser extends Controller_Rest
{
    protected  $format = 'json';
    
    public function action_RegeditUser(){
        
        //post parameter
        $mail = Input::post('mail');
        $password = Input::post('password');
        $password_again = Input::post('password_again');
        $username = Input::post('username');
        
        //check validation
        if (($mail == "") || ($password == "") or ($password_again == "") or ($username == "")) {
            $this->Response(
                array(
                        'status'  => 401,
                        'message'   => "Invalid Argument",
                )
            );
            return;
        }
        
        //search user account
        $m = new Model_mUser();
        
        $data = array();
        
        try {
            $data_user = $m->search_user($mail);
        } catch (Exception $e) {
            $this->Response(
                    array(
                            'status'    => 500,
                            'message'   => "InternalÊServerÊError",
                    )
            );
            return;
        }        
        
        if ($data_user != 0) {
            $this->Response(
                    array(
                            'status'    => 402,
                            'message'   => "Exist User",
                    )
            );
            return;
        }
        
        if ($password != $password_again) {
            $this->Response(
                array(
                        'status'  => 403,
                        'message'   => "Not match password",
                )
            );
            return;
        }
        //echo 'aaa';exit;
        $insert_id = $m->regedit_user($mail, $password, $username);
        
        $this->Response(
                array(
                        'status'  => 200,
                        'message'   => "regedit user id " .$insert_id . " successful",
                )
        );
        

    }

    //Add Date: 19/09/2014 Name: TranQuocDung Start
    public function action_banuser()
    {
        //post parameter
        $user_id = Input::post('user_id');
        $ban = Input::post('ban');
        $token = Input::post('token');

        //check validation
        if (($user_id == "") || ($ban == "") or ($token == "")) {
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

        //Set Ban
        try {
            $set_ban = $muser->set_ban_user($user_id,$ban);
             

        } catch (Exception $e) {
            $this->Response(
                    array(
                            'status'    => 500,
                            'message'   => "InternalÊServerÊError",
                    )
            );
            return;
        }

        //Insert history User

        $mhis = new Model_historyuser();

        $id_his = $mhis->insert_his($data_token['user_id'],$user_id);

        $this->Response(
                    array(
                            'status'    => 200,
                            'message'   => "Successful",
                    )
            );
            return;

        
    }
    public function action_setroleuser()
    {
        //post parameter
        $user_id = Input::post('user_id');
        $role = Input::post('role');
        $token = Input::post('token');
        
        //check validation
        if (($user_id == "") || ($role == "") or ($token == "")) {
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

        //Set Role
        try {
            $set_role = $muser->set_role_user($user_id,$role);
             

        } catch (Exception $e) {
            $this->Response(
                    array(
                            'status'    => 500,
                            'message'   => "InternalÊServerÊError",
                    )
            );
            return;
        }

        //Insert history User

        $mhis = new Model_historyuser();

        $id_his = $mhis->insert_his($data_token['user_id'],$user_id);

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