<?php  
	class Route_Log
	{
		private $uri;
		private $db;

		public function __construct($uri, &$db)
		{
			$this->uri = $uri;
			$this->db  = $db;

			if($_SERVER['REQUEST_METHOD'] == 'POST') {
				if(count($this->uri) == 2) {
					$this->record_view();
				}
			}
		}

		private function record_view()
		{
			$record_view = "insert into view_log(view_date, view_ip, view_resource) values(CURRENT_TIMESTAMP,?,?)";
			$stmt = $this->db->prepare($record_view);
			$stmt->execute(array($_SERVER['REMOTE_ADDR'], $this->uri[1]));
		}
	}
?>