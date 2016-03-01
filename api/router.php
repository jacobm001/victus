<?php 
	class Router
	{
		private $uri;
		private $db;

		public function __construct($uri, &$db)
		{
			$this->uri = explode('/');
			$this->db  = $db;
		}
	}
?>