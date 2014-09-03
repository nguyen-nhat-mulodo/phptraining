<?php

use \Model_mRegeditUser;
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
        $m = new Model_mRegeditUser();
        
        $data = array();
        
        $data_user = $m->search_user($mail);
        
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
        return;

    }
}