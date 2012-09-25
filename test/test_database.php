<?php

include '../moondragon.database.php';

Database::connect('mysql', 'localhost', 'root', '', 'test');

$db = Database::getManager();

try {
	$result = $db->query('SELECT * FROM table1');
}
catch(QueryException $e) {
	echo $e->getMessage();
	//$result = new DBResult(DB_EMPTY_RESULT);
}


while($row = $result->fetch_object()) {
	echo $row->name.' '.$row->value.'<br/>';
}

//echo 'number of results '.$result->rowsNumber().'<br/>';
