<?php
class Model_mHistoryUser extends \Model{
    
    public function save_historyuser($user_id,$admin_id,$content){
        $temp = new DateTime();
        $dt = $temp->format('Y-m-d H:i:s');
        //$exp = new DateTime();
        $exp = date('Y-m-d H:i:s',strtotime("+2 day",strtotime($dt)));
        //$exp1 = $exp->format('Y-m-d H:i:s');
        
        $data_historyuser = array(
                'user_id'      => $user_id,
                'admin_id'       => $admin_id,
                'content'		=> $content,
                'created'       => $dt,
                );
        //var_dump($data_token);exit;

        list($insert_id, $rows_affected) = DB::insert('history_user')->set($data_historyuser)->execute();
        
        //var_dump($insert_id);exit;
        // echo "string";
        return $insert_id;
    }
}