<?php 
	class Gatekeeper
	{
		private $ip;
		private $db;
		private $uri;
		private $session_key;
		private $session_exp;

		public function __construct(&$db)
		{
			$this->db = $db;
			$this->ip = $_SERVER['REMOTE_ADDR'];

			$this->session_key = null;
			$this->session_exp = null;
			$this->uri         = explode('/', $_REQUEST['uri']);

			$this->check_ban_record();
			$this->check_for_vars();

			if($this->session_key != null)
				$this->validate_session();

			$this->create_route_obj();
		}

		private function check_ban_record()
		{
			$query_text = 'select ban_reason from banned_ip where ban_ip = ? and ban_active_ind = "Y"';
			$stmt = $this->db->prepare($query_text);
			$stmt->execute(array($this->ip));

			if($stmt->fetchAll(PDO::FETCH_ASSOC) != FALSE)
				die('You were banned!');
		}

		private function check_for_vars()
		{
			if(isset($_REQUEST['session_key']))
				$this->session_key = $_REQUEST['session_key'];
			
			if(isset($_REQUEST['user']))
				$this->user = $_REQUEST['user'];
			
			if(isset($_REQUEST['pass']))
				$this->pass = $_REQUEST['pass'];
		}

		private function validate_session()
		{
			$query_text = 'select users.user_id, users.username, sessions.session_expires from sessions join users on sessions.session_user = users.user_id where session_key = ? and current_timestamp between session_start and session_expires;';
			$stmt = $this->db->prepare($query_text);

			$stmt->execute(array($this->session_key));
			$result = $stmt->fetch();

			if($result == FALSE)
				die('expired session');

			$this->user_id     = $result['user_id'];
			$this->username    = $result['username'];
			$this->session_exp = $result['session_expires'];
		}

		private function create_route_obj()
		{
			switch($this->uri[0])
			{
				case 'recipes':
					new Route_Recipes($this->uri, $this->db);
					break;
				case 'auth':
					new Route_Auth($this->uri, $this->db);
					break;
				case 'log':
					new Route_Log($this->uri, $this->db);
					break;
				default:
					var_dump($this->uri);
			}
		}
	}
?>
