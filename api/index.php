<?php
	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ALL); 

	include 'start.php';
	include 'gatekeeper.php';
	include 'recipe.php';

	$gk = new Gatekeeper($_SERVER['REMOTE_ADDR'], $db);

	// basic view recording
	$record_view = "insert into view_log(view_date, view_ip, view_resource) values(CURRENT_TIMESTAMP,?,?)";
	$stmt = $db->prepare($record_view);
	$stmt->execute(array($_SERVER['REMOTE_ADDR'], 'ALL_RECIPES'));
	
	$query = "select * from one_line_recipes";
	$result = $db->query($query);

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
	//echo json_encode($recipes, JSON_PRETTY_PRINT);
?>
