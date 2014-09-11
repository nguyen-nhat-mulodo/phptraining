<?php
class Model_HistoryUser extends Orm\Model
{
	protected static $_properties = array('id','user_id', 'admin_id', 'content', 'created');
	protected static $_soft_delete = array(
		'id' => 'deleted',
		'mysql_timestamp' => false,
	);
	protected static $_table_name = 'historyuser';

}