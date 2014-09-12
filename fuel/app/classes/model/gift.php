<?php
class Model_Gift extends Orm\Model
{
	protected static $_properties = array('id','sender_id', 'reciever_id', 'image','thumbnail', 'created');
	protected static $_soft_delete = array(
		'user_id' => 'deleted',
		'mysql_timestamp' => false,
	);
}