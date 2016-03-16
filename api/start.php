<?php
	if( file_exists('../storage/data.db') ) {
		$db = new PDO('sqlite:../storage/data.db');
	} 
	else {
		$db = new PDO('sqlite:../storage/data.db');
		$sql = file_get_contents('../storage/schema.sql');
		$db->exec($sql);
	}
?>