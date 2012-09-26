<?php

include '../moondragon.database.php';

Database::connect('mysql', 'localhost', 'root', '', 'test');

$db = Database::getManager();

try {
	$result = $db->query('SELECT * FROM table1');
}
catch(QueryException $e) {
	echo $e->getMessage();
	die();
	//$result = new DBResult(DB_EMPTY_RESULT);
}


foreach($result as $row) {
	echo $row->name.' '.$row->value.'<br/>';
}

echo 'number of results '.$result->numRows().'<br/>';
