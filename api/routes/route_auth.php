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

			$result = $stmt->fetch();
			if(empty($result))
				die("username/password invalid");
			else
				return $result['user_id'];
		}
	}
?>