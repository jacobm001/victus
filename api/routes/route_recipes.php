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
				if(count($this->uri) == 1) {
					$this->get_recipes();
					$this->record_view('ALL_RECIPES');
				}

				else if(count($this->uri) == 3)
					$this->get_recipe();
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

		private function get_recipe()
		{
			if($this->uri[1] == 'id') {
				$query = "select * from one_line_recipes where recipe_id = ?";
				$this->record_view('recipe/id/' . $this->uri[3]);
			}
			else if($this->uri[1] == 'name') {
				$query = "select * from one_line_recipes where recipe_name = ?";
				$this->record_view('recipe/name/' . $this->uri[3]);
			}
			else
				die("bad `recipe/:something/`");

			$stmt = $this->db->prepare($query);
			$stmt->execute(array($this->uri[2]));

			$result = $stmt->fetch();
			if($result != Null) {
				$recipe = new Recipe($result["recipe_id"], $result["recipe_name"], 
						$result["recipe_yields"], $result["recipe_notes"], $result["recipe_directions"]);

				echo json_encode($recipe);
			}
		}

		private function record_view($obj)
		{
			$record_view = "insert into view_log(view_date, view_ip, view_resource) values(CURRENT_TIMESTAMP,?,?)";
			$stmt = $this->db->prepare($record_view);
			$stmt->execute(array($_SERVER['REMOTE_ADDR'], $obj));
		}
	}
?>