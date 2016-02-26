<?php
	class User
	{
		// user information
		private $id;
		private $username;
		private $password;
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
				$this->validate_session();
		}

		public function set_credentials($user, $pass)
		{
			$this->username = $user;
			$this->password = $pass;
		}

		public function validate_credentials()
		{
			$query_text = 'select name from users where username = ? and password = ?;';
			$stmt = $this->db->prepare($query_text);
			$stmt->execute(array(
				$this->username,
				sha1($this->password)
			));

			$result = $stmt->fetchAll();
			if($result == FALSE)
				return FALSE;

			$this->name = $result[0]['name'];
			return TRUE;
		}

		public function create_user()
		{
			$query_text = 'insert into users(username, password) values(?,?);';
			$stmt = $this->db->prepare($query_text);

			$result = $stmt->execute(array(
				$this->username,
				sha1($this->password)
			));

			if($result == FALSE)
				return FALSE;
			else
				return TRUE;
		}

		private function set_defaults()
		{
			$this->userlevel = 'guest';
		}

		private function validate_session()
		{
			$query_text = 'select 1 from sessions where session_key = ? and CURRRENT_TIMESTAMP between session_start and session_expires;';
			$stmt = $this->db->prepare($query_text);

			$stmt->execute(array($this->session_key));

			if($result == FALSE)
				die('expired session');
				// handle expired session
		}
	}
?>