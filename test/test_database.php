<?php

include '../moondragon.database.php';

$conn = new DBConnection('mysql', 'localhost', 'root', '', 'test');

$db = new DBManager($conn);

$query = new $db->newQuery('SELECT %s, %s FROM `table`', array('name', 'value'));

try {
	$result = $query->getResult();
}
catch(QueryException $e) {
	$result = new DBResult(DB_EMPTY_RESULT);
}


foreach($result as $row) {
	echo $row->name.'<br/>';
}

echo 'number of results '.$result->rowsNumber().'<br/>';
