<?php 
	class Gatekeeper
	{
		private $ip;
		private $db;

		public function __construct($ip, &$db)
		{
			$this->ip = $ip;
			$this->db = $db;

			$this->check_ban_record();
		}

		private function check_ban_record()
		{
			$query_text = 'select ban_reason from banned_ip where ban_ip = ?';
			$stmt = $this->db->prepare($query_text);
			$stmt->execute(array($this->ip));

			if($stmt->fetchAll(PDO::FETCH_ASSOC) != FALSE) {
				die('You were banned!');
			}
		}
	}
?>