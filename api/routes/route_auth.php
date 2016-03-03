<?php
	class Route_Auth
	{
		private $uri;
		private $db;

		public function __construct($uri, &$db)
		{
			$this->uri = $uri;
			$this->db  = $db;

			if($_SERVER['REQUEST_METHOD'] == 'POST') {
				if(count($this->uri) == 2)
					$this->check_route();
				else
					die("bad `auth/:route`");
			}
		}

		private function check_route()
		{
			if($this->uri[1] == 'login')
				$this->login();
			else if($this->uri[1] == 'logout')
				$this->logout();
			else if($this->uri[1] == 'create')
				$this->create();
		}

		private function login()
		{
			$this->check_post_vars();
			$user_id = $this->check_credentials();
		}

		private function logout()
		{
		}

		private function check_post_vars()
		{
			if(!isset($_POST['user']) or !isset($_POST['pass']))
				die("Missing a credential");
		}

		private function check_credentials()
		{
			$query = "select user_id from users where username = ? and password = ?;";
			$stmt  = $this->db->prepare($query);
			$stmt->execute(array($_POST['user'], hash('sha256', $_POST['pass'])));

			$result = $stmt->fetchAll();
			if($result == FALSE)
				die("username/password invalid");
			else
				return $result[0]['user_id'];
		}

		private function create()
		{
			// $query_text = 'insert into users(username, password) values(?,?);';
			// $stmt = $this->db->prepare($query_text);

			// $result = $stmt->execute(array(
			// 	$this->username,
			// 	sha1($this->password)
			// ));

			// if($result == FALSE)
			// 	return FALSE;
			// else
			// 	return TRUE;
		}
	}
?>