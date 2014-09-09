<?php

class Model_mPost extends Model{
    
    public  function create_post($userid, $title, $content){
        $temp = new DateTime();
        $dt = $temp->format('Y-m-d H:i:s');
        
        $data_post = array(
                'user_id' => $userid,
                'title'   => $title,
                'content' => $content,
                'created' => $dt,);
        //echo 'bbb';exit;
        list($insert_id, $rows_affected) = DB::insert('posts')->set($data_post)->execute();
    
        //var_dump($insert_id);exit;
    
        return $insert_id;
    }
}