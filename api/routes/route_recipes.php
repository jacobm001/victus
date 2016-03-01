<?php 
	class Route_Recipes
	{
		private $uri;
		private $db;

		public function __construct($uri, &$db)
		{
			$this->uri = $uri;
			$this->db  = $db;

			if($_SERVER['REQUEST_METHOD'] == 'GET') {
				$this->get_recipes();
				$this->record_view('ALL_RECIPES');
			}
		}

		private function get_recipes()
		{
			$query = "select * from one_line_recipes";
			$result = $this->db->query($query);

			$recipes = [];
			
			if( $result != Null ) {
				foreach($result as $row)
				{
					$recipes[] = new Recipe($row["recipe_id"], $row["recipe_name"], 
						$row["recipe_yields"], $row["recipe_notes"], $row["recipe_directions"]);

					end($recipes)->set_tag($row["recipe_tags"]);
					end($recipes)->set_ingr($row["recipe_ingredients"]);
				}
			}

			echo json_encode($recipes);
		}

		private function record_view($obj)
		{
			$record_view = "insert into view_log(view_date, view_ip, view_resource) values(CURRENT_TIMESTAMP,?,?)";
			$stmt = $this->db->prepare($record_view);
			$stmt->execute(array($_SERVER['REMOTE_ADDR'], 'ALL_RECIPES'));
		}
	}
?>