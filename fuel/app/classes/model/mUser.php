<?php
class Model_mUser extends \Model{
    
    public function  search_user($mail){
        
       $sql = "SELECT * FROM Users WHERE email = '" .$mail ."'";
            
       $select = DB::query($sql)->execute();
       //var_dump(count($select));exit;
       if (count($select) == 0) {
            return 0;
       }
       return $select->as_array();
    }
    
    public function  search_login($mail, $password){
    
        $query = DB::select("*");
        $query->from("Users");
        $query->where("email","=",$mail);        
        $query->where("password","=",$password);
        
        $result = $query->execute();
                
        if (count($result) == 0) {
            return 0;
        }
        return $result->current();
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
        
        //var_dump($insert_id);exit;
        
        return $insert_id;
    }
}