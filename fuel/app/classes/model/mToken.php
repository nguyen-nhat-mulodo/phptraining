<?php
class Model_mToken extends Model{
    
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
    
    public function search_token($token_id){
        $temp = new DateTime();
        $dt = $temp->format('Y-m-d H:i:s'); 
        
        $query = DB::select("*");
        $query->from("token");
        $query->where("token_id", "=", $token_id);
        $query->where("expired", ">=", $dt);
        
        $result = $query->execute();
        //var_dump(DB::last_query());exit;
        if (count($result) == 0) {
            return 0;
        }
        //var_dump($result->current());exit;
        return $result->current();
    }
}