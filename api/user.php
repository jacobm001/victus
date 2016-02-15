<?php
	class User
	{
		// user information
		private $id;
		private $username;
		private $name;
		private $userlevel;
		
		// session information
		private $session_key;
		private $session_start;
		private $session_expires;

		// $db is a reference to a shared connection
		private $db;

		public function __construct(&$db, $session_key = NULL)
		{
			$this->db = $db;

			if( $session_key == NULL )
				$this->set_defaults();
			else
				$this->continue_session();
		}

		private function set_defaults()
		{

		}

		private function continue_session()
		{

		}
	}
?>