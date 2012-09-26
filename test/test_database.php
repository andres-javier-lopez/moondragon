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

$query = $db->getQuery('SELECT `%s`, `%s` FROM `%s` WHERE `name` = "%s" ');
$query->addParams(array('name', 'value'));
$query->addParam('table1');
$query->addParam('" OR 1 = 1; #');

try {
	$result = $query->getResult();
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

echo '<br/><pre>'.$db->showQueryHistory().'</pre><br/>';
