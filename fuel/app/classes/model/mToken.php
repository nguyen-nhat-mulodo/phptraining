<?php
class Model_mToken extends \Model{
    
    public function save_token($token_id, $user_id){
        $temp = new DateTime();
        $dt = $temp->format('Y-m-d H:i:s');
        //$exp = new DateTime();
        $exp = date('Y-m-d H:i:s',strtotime("+2 day",strtotime($dt)));
        //$exp1 = $exp->format('Y-m-d H:i:s');
        
        $data_token = array(
                'token_id'      => $token_id,
                'user_id'       => $user_id,
                'created'       => $dt,
                'expired'       => $exp,);
        //var_dump($data_token);exit;
        list($insert_id, $rows_affected) = DB::insert('token')->set($data_token)->execute();
        
        //var_dump($insert_id);exit;
        
        return $insert_id;
    }
}