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
			else if($this->uri[1] == 'validate')
				$this->validate();
		}

		private function login()
		{
			$this->check_post_vars('login');
			$user_id     = $this->check_credentials();
			$session_key = $this->create_session($user_id);
			$session_exp = $this->get_session_exp($session_key);

			$ret['status']      = 'success';
			$ret['session_key'] = $session_key;
			$ret['session_exp'] = $session_exp;
			echo json_encode($ret);
		}

		private function validate()
		{
			$this->check_post_vars('validate');
			$this->check_key();
		}

		private function check_post_vars($type)
		{
			if($type == 'login') {
				if(!isset($_POST['user']) or !isset($_POST['pass']))
					die("Missing a credentials");
			}
			else if($type == 'validate') {
				if(!isset($_POST['key']))
					die("Missing key");
			}
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

		private function check_key()
		{
			$query = "select session_expires from sessions where session_key = ? and current_timestamp between session_start and session_expires";
			$stmt = $this->db->prepare($query);
			$stmt->execute(array($_POST['key']));

			$result = $stmt->fetchAll();
			if($result == FALSE)
				die("key is no good");
			else
				echo $result[0]['session_expires'];
		}

		private function create_session($user_id)
		{
			$query = "insert into sessions(session_user, session_key) values(?,?)";
			$time  = strtotime("now");
			$key   = md5( $user_id . $time );
			
			$stmt  = $this->db->prepare($query);
			$stmt->execute(array(
				$user_id,
				$key
			));

			return $key;
		}

		private function get_session_exp($session_key)
		{
			$query = "select session_expires from sessions where session_key = :session_key";
			$stmt  = $this->db->prepare($query);
			$stmt->bindParam(':session_key', $session_key);
			$stmt->execute();

			$result = $stmt->fetch();
			return $result[0];
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
