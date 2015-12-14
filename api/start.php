<?php
	if( file_exists('../data/data.db') ) {
		$db = new PDO('sqlite:../data/data.db');
	} 
	else {
		$db = new PDO('sqlite:../data/data.db');
		$sql = file_get_contents('../data/schema.sql');
		$db->exec($sql);
	}
?>