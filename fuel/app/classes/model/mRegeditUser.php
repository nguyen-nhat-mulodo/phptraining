<?php
class Model_mRegeditUser extends \Model{
    
    public function  search_user($mail){
        $sql = "SELECT * FROM Users WHERE email = '" .$mail ."'";
        
        $select = DB::query($sql)->execute();
        //var_dump(count($select));exit;
        if (count($select) == 0) {
            return 0;
        }
        return $select->as_array();
    }
    
    public  function regedit_user($mail, $password, $username){
        $temp = new DateTime();
        $dt = $temp->format('Y-m-d H:i:s');
        $data_user = array(
                'user_name' => $username,
                'email'     => $mail,
                'password'  => $password,
                'created'   => $dt,);
        //echo 'bbb';exit;
        list($insert_id, $rows_affected) = DB::insert('Users')->set($data_user)->execute();
        
        var_dump($insert_id);exit;
        
        return $insert_id;
    }
}