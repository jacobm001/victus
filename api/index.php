<?php
	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ALL); 

	include 'start.php';
	include 'gatekeeper.php';
	include 'router.php';
	include 'routes/route_recipes.php';
	include 'routes/route_auth.php';
	include 'routes/route_log.php';
	include 'recipe.php';

	$gk    = new Gatekeeper($db);
	$route = new Router($db);
?>
