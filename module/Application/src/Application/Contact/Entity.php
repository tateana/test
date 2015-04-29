<?php
namespace Application\Contact;

class Entity {

	private $id;
	private $name;
	private $email;
	private $subject;
	private $message;

	
	public function getId() {
		return $this->id;
	}

	public function setId($id) {
		$this->id = $id;
	}

	public function getName() {
		return $this->name;
	}

	public function setName($name) {
		$this->name = $name;
	}

	public function getEmail() {
		return $this->email;
	}

	public function setEmail($email) {
		$this->email = $email;
	}

	public function getSubject() {
		return $this->subject;
	}

	public function setSubject($subject) {
		$this->subject = $subject;
	}

	public function getMessage() {
		return $this->message;
	}

	public function setMessage($message) {
		$this->message = $message;
	}

}
