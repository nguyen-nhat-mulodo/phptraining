<?php
class Model_mGift extends \Model{
    
    public function save_gift($sender_id,$reciever_id,$image,$thumbnail){
        $temp = new DateTime();
        $dt = $temp->format('Y-m-d H:i:s');
        //$exp = new DateTime();
        $exp = date('Y-m-d H:i:s',strtotime("+2 day",strtotime($dt)));
        //$exp1 = $exp->format('Y-m-d H:i:s');
        
        $data_gift = array(
                'sender_id'      => $sender_id,
                'reciever_id'    => $reciever_id,
                'image'		    => $image,
                'thumbnail'     => $thumbnail,
                'created'       => $dt,
                );
        //var_dump($data_token);exit;

        list($insert_id, $rows_affected) = DB::insert('gift')->set($data_gift)->execute();
        
        //var_dump($insert_id);exit;
        // echo "string";
        return $insert_id;
    }
}