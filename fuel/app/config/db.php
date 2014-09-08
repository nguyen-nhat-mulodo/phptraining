<?php
/**
 * Use this file to override global defaults.
 *
 * See the individual environment DB configs for specific config information.
 */

return array(
		// a MySQL driver configuration
		'development' => array(
				'type'           => 'mysql',
				'connection'     => array(
						'hostname'       => 'localhost',
						'port'           => '8889',
						'database'       => 'training',
						'username'       => 'root',
						'password'       => 'root',
						'persistent'     => false,
						'compress'       => false,
				),
				'identifier'     => '`',
				'table_prefix'   => '',
				'charset'        => 'utf8',
				'enable_cache'   => true,
				'profiling'      => false,
				'readonly'       => false,
		),
		
		// a PDO driver configuration, using PostgreSQL
		'production' => array(
				'type'           => 'pdo',
				'connection'     => array(
						'dsn'            => 'pgsql:host=localhost;dbname=fuel_db',
						'username'       => 'your_username',
						'password'       => 'y0uR_p@ssW0rd',
						'persistent'     => false,
						'compress'       => false,
				),
				'identifier'     => '"',
				'table_prefix'   => '',
				'charset'        => 'utf8',
				'enable_cache'   => true,
				'profiling'      => false,
				'readonly'       => array('slave1', 'slave2', 'slave3'),
		),
		
		'slave1' => array(
		// configuration of the first production readonly slave db
		),
		
		'slave2' => array(
		// configuration of the second production readonly slave db
		),
		
		'slave3' => array(
		// configuration of the third production readonly slave db
		),
);
