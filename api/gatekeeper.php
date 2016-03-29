<?php 
	class Gatekeeper
	{
		private $ip;
		private $db;
		private $id;
		private $user;
		private $pass;
		private $session_key;
		private $session_exp;

		public function __construct(&$db)
		{
			$this->ip = $_SERVER['REMOTE_ADDR'];
			$this->db = $db;

			$this->id          = null;
			$this->user        = null;
			$this->pass        = null;
			$this->session_key = null;
			$this->session_exp = null;

			$this->check_ban_record();
			$this->check_for_vars();

			if($this->session_key != null) {
				$this->validate_session();
			}
		}

		private function check_ban_record()
		{
			$query_text = 'select ban_reason from banned_ip where ban_ip = ? and ban_active_ind = "Y"';
			$stmt = $this->db->prepare($query_text);
			$stmt->execute(array($this->ip));

			if($stmt->fetchAll(PDO::FETCH_ASSOC) != FALSE) {
				die('You were banned!');
			}
		}

		private function check_for_vars()
		{
			if(isset($_REQUEST['session']))
				$this->session_key = $_REQUEST['session'];
			
			if(isset($_REQUEST['user']))
				$this->user = $_REQUEST['user'];
			
			if(isset($_REQUEST['pass']))
				$this->pass = $_REQUEST['pass'];
		}

		private function validate_session()
		{
			$query_text = 'select users.user_id, users.username, sessions.session_expires from sessions join users on sessions.user_id = users.user_id where session_key = ? and CURRRENT_TIMESTAMP between session_start and session_expires;';
			$stmt = $this->db->prepare($query_text);

			$stmt->execute(array($this->session_key));
			$result = $stmt->fetch();

			if($result == FALSE)
				die('expired session');

			$this->user_id     = $result['user_id'];
			$this->username    = $result['username'];
			$this->session_exp = $result['session_expires'];
		}
	}
?>
