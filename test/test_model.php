<?php

include '../moondragon.database.php';

// Create a Model class
$config['table'] = 'table1';
$config['fields'] = array('name#s', 'val' => 'value');
$config['relations'] = array('table2.id_table2', 'otra_tabla' => 'table3.id_table3');
$config['joins'] = array('table2' => array('name2', 'val1' => 'value'), 'table3' => array('value'));

ModelLoader::addModel('test', $config);

// Init connection
Database::connect('mysql', 'localhost', 'root', '', 'test');
$db = Database::getManager();

// Instance Model
$model = ModelLoader::getModel('test');

$dataset = $model->getDataset();
$dataset->name = 'prueba';
$dataset->value = 1;
$dataset->id_table2 = 1;

try {
	$id = $model->create($dataset);
}
catch(CreateException $e) {
	$id = 0;
}


// Make a select query to the model
$reader = $model->getReader();

// $reader->setFields(array('name', 'table2.name'));
$reader->setOrder('id DESC');
$reader->orderBy('name', 'DESC');

try {
	$rows = $reader->getRows();
}
catch(ReadException $e) {
	echo '<pre>';
	echo $e->getMessage();
	echo '</pre><br/>';
	$rows = array();
}

foreach($rows AS $row) {
	echo $row->name.' '.$row->val.' '.$row->name2.' '.$row->val1.' '.$row->table3_value.'<br/>';
}

echo '<br/>datos filtrados:<br/>';

$reader->addWhere('1 = 1');
$reader->addWhere('`%s` IS NOT %s', array('name_table1', 'NULL'));
$reader->addWhere('name', 'prueba');
$reader->setLimit(3);

try {
	$rows = $reader->getRows();
}
catch(ReadException $e) {
	echo '<pre>';
	echo $e->getMessage();
	echo '</pre><br/>';
	$rows = array();
}

foreach($rows AS $row) {
	echo $row->name.' '.$row->val.'<br/>';
}

// Insert rows to a model
$dataset = $model->getDataset();
$dataset->name = 'hello';
$dataset->value = 42;
$dataset->id_table2 = 1;

try {
	$id = $model->create($dataset);
}
catch(CreateException $e) {
	$id = 0;
}


// Read and delete rows from a model
try {
	$data = $model->getData($id);
}
catch(ReadException $e) {
	$data = new stdClass();
	$data->name = '';
	$data->value = '';
	echo '<pre>'.$e.'</pre>';
}
echo '<br/>los datos son: '.$data->name.' '.$data->value.'<br/>';
try {
	$model->delete($id);
}
catch(DeleteException $e) {
	echo $e->getMessage();
}

echo '<pre>';
echo $db->showQueryHistory();
echo '</pre>';

// Create another Model
$new_model = $db->getModel();

$new_model->setTable('table1');
$new_model->setFields(array('name#s'));

// Insert a row to the model
$dataset = $new_model->getDataset();
$dataset->name = 'hello world!';

try {
	$id = $new_model->create($dataset);
}
catch(CreateException $e) {
	echo $e->getMessage();
	$id = 0;
}

// Update a row in the model
try {
	$dataset = $new_model->getData($id);
}
catch(ReadException $e) {
	echo '<pre>';
	echo $e;
	echo $db->showQueryHistory();
	echo '</pre>';
	die();
}
$dataset->name = 'Hola Mundo!';

try {
	$new_model->update($id, $dataset);
}
catch (UpdateException $e) {}

try {
	$data = $new_model->getData($id);
}
catch(ReadException $e) {
	$data = new stdClass();
	$data->name = '';
}
echo $data->name.'<br/>';

try {
	$new_model->deleteWhere(array('name' => 'hola', 'value' => 'mundo'));
	$new_model->deleteWhere('`name_table1` != "hello"');
}
catch(DeleteException $e) {}

echo '<pre>';
echo $db->showQueryHistory();
echo '</pre>';

