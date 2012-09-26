<?php

include '../moondragon.database.php';

Database::connect('mysql', 'localhost', 'root', '', 'test');

$db = Database::getManager();

try {
	$result = $db->query('SELECT * FROM table1');
}
catch(QueryException $e) {
	echo $e->getMessage().'<br/>';
	$result = $db->getEmptyResult();
}


foreach($result as $row) {
	echo $row->name.' '.$row->value.'<br/>';
}

try {
	echo 'number of results '.$result->numRows().'<br/>';
}
catch(EmptyResultException $e) {
	echo 'No valid results<br/>';
}

try {
	$result = $db->query('BAD QUERY');
}
catch(QueryException $e) {
	echo $e->getMessage().'<br/>';
	$result = $db->getEmptyResult();
}


foreach($result as $row) {
	echo 'DO NOT PRINT';
}

try {
	echo 'number of results '.$result->numRows().'<br/>';
}
catch(EmptyResultException $e) {
	echo 'No valid results<br/>';
}
