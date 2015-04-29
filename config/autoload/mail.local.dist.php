<?php
return array(
    'mail' => array(
		'from'  => 'test@gmail.com', 
		'to'  => 'test@gmail.com',
		'subject' => 'Notification',
		'transport' => 'file',
		'transport_options' => array('path' => __DIR__.'/../../data/mail'),
		/* 'transport' => 'smtp',
		'transport_options' => array(
			'host' => 'smtp.gmail.com',
			'port' => '465',
			'connection_class' => 'plain',
			'connection_config' => array(
			    'username' => 'test@gmail.com',
			    'password' => 'password',
			    'ssl'      => 'ssl'
			),
		 ) */
    ),
);