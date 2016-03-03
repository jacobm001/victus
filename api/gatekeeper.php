<?php 
	class Gatekeeper
	{
		private $ip;
		private $db;
		private $user;
		private $pass;
		private $userlevel;
		private $session_key;

		public function __construct(&$db)
		{
			$this->ip = $_SERVER['REMOTE_ADDR'];
			$this->db = $db;

			$this->check_ban_record();
			if($this->check_for_session()) {
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

		private function check_for_session()
		{
			if(isset($_REQUEST['session'])) {
				$this->session_key = $_REQUEST['session'];
				return True;
			}
			else
				return False;
		}

		private function validate_session()
		{
			$query_text = 'select user.username from sessions join users on sessions.user_id = users.user_id where session_key = ? and CURRRENT_TIMESTAMP between session_start and session_expires;';
			$stmt = $this->db->prepare($query_text);

			$stmt->execute(array($this->session_key));
			$result = $stmt->fetch();

			if($result == FALSE)
				die('expired session');

			$this->user = $result['username'];
		}
	}
?>
