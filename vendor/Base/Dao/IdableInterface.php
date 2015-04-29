<?php 

namespace Base\Dao;

interface IdableInterface {
	
	public function getId();
	
	public function setId($id);
}