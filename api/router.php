<?php 
	class Router
	{
		private $uri;
		private $db;

		public function __construct(&$db)
		{
			$this->uri = explode('/', $_REQUEST['uri']);
			$this->db  = $db;
			$this->create_route_obj();
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
				default:
					var_dump($this->uri);
			}
		}
	}
?>
