<?php

class Model_gift extends Model{
    
    public  function add_gift($send, $get, $image,$thumb){
        $temp = new DateTime();
        $dt = $temp->format('Y-m-d H:i:s');
        
        $data_post = array(
                'id_user_send' => $send,
                'id_user_get'   => $get,
                'image' => $image,
                'thumb' => $thumb,
                'created' => $dt,
                );
        //echo 'bbb';exit;
        list($insert_id, $rows_affected) = DB::insert('gift')->set($data_post)->execute();
    
        //var_dump($insert_id);exit;
    
        return $insert_id;
    }
}