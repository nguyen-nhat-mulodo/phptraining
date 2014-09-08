<?php
class Model_Users extends Orm\Model
{
	protected static $_properties = array('id','email', 'user_name', 'password', 'created');
	protected static $_soft_delete = array(
		'user_id' => 'deleted',
		'mysql_timestamp' => false,
	);
}