<?php
return array(
			'db' => array(
				'driver' => 'Pdo',
				'dsn' => 'mysql:dbname=test;host=localhost',
				'username' => 'username', 
				'password' => 'password',
				'driver_options' => array(
						PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES \'UTF8\''
				)
			),
		);
