<?php
namespace Zero\library;


class Model
{

	protected $db; //a database instance

	public function __construct()
	{
		$this->db = Factory::getDatabase();
		$this->cache = Factory::getCache();
	}			


}