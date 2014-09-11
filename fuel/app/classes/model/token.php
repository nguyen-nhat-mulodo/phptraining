<?php

class Model_Token extends \Orm\Model
{
	protected static $_properties = array(
			'id',
			'expired',
			'token_id',
			'user_id',			
	);
	
	protected static $_table_name = 'token';
}