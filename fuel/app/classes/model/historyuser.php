<?php
class Model_historyuser extends \Model{
    
    public function  insert_his($id_admin,$id_user){
     
        $temp = new DateTime();
        $dt = $temp->format('Y-m-d H:i:s');
       $data_his = array(
                'id_admin' => $id_admin,
                'id_user'     => $id_user,
                'modified'  => $dt,
                );
        //echo 'bbb';exit;
        list($insert_id, $rows_affected) = DB::insert('history_user')->set($data_his)->execute();
        
        //var_dump($insert_id);exit;
        
        return $insert_id;
    }

    
}