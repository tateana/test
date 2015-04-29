<?php
namespace Application\Contact;


class Dao extends \Base\Dao\Dao {
	
	public function __construct() {
		$fields = array(
			'id' => 'id',
			'name' => 'name',
			'email' => 'email',
			'subject' => 'subject',
			'message' => 'message',
		);
		
		parent::__construct('messages', 'id', $fields);
	}
	
	public function getNewEntity() {
		return new Entity();
	}
}