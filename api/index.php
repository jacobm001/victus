<?php
	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ALL); 

	include 'start.php';
	include 'gatekeeper.php';
	include 'router.php';
	include 'routes/route_recipes.php';
	include 'routes/route_auth.php';
	include 'user.php';
	include 'recipe.php';

	$gk    = new Gatekeeper($_SERVER['REMOTE_ADDR'], $db);
	$route = new Router($_REQUEST['uri'], $db);
?>
