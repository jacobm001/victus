<?php
	//ini_set('display_errors', 1);
	//ini_set('display_startup_errors', 1);
	//error_reporting(E_ALL); 

	include 'recipe.php';

	$db = new PDO('sqlite:data.db');
	
	$query = "select * from one_line_recipes";

	$result = $db->query($query);

	$recipes = [];
	
	foreach($result as $row)
	{
		$recipes[] = new Recipe($row["recipe_id"], $row["recipe_name"], 
			$row["recipe_yields"], $row["recipe_notes"], $row["recipe_directions"]);

		end($recipes)->set_tag($row["recipe_tags"]);
		end($recipes)->set_ingr($row["recipe_ingredients"]);
	}

	echo json_encode($recipes);
	//echo json_encode($recipes, JSON_PRETTY_PRINT);
?>
