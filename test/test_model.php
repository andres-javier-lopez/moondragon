<?php

include '../moondragon.database.php';

// Create a Model class
$config['table'] = 'table';
$config['fields'] = array('name', 'value');
$config['relations'] = array('table2.id_table2', 'table3.id_table3');

// Init connection
$conn = new DBConnection('mysql', 'localhost', 'root', '', 'test');
$db = new DBManager($conn);

// Instance Model
$model = $db->getModel($config);

// Make a select query to the model
$reader = $model->getReader();

$reader->setFields(array('name', 'table2.name'));
$reader->setOrder('id_table ASC, name DESC');

try {
	$rows = $reader->getRows();
}
catch(ReaderException $e) {
	$rows = array();
}

foreach($rows AS $row) {
	echo $row->name_table1.' '.$row->name_table2.'<br/>';
}

// Insert two rows to a model
$dataset = $model->getDataset();
$dataset->name = 'hello';
$dataset->value = 'world';

$dataset2 = $model->getDataset(array('name' => 'hello2', 'value' => 'world2'));

try {
	$inserts = $model->insert(array($dataset, $dataset2));
}
catch(InsertException $e) {
	$inserts = array();
}

// Read and delete rows from a model
foreach($inserts as $id) {
	$data = $model->getData($id);
	echo $data->name.' '.$data->value.'<br/>';
	try {
		$model->delete($id);
	}
	catch(DeleteException $e) {}
}

// Create another Model
$new_model = $db->getModel();

$new_model->table = 'table';
$new_model->fields = array('name');

// Insert a row to the model
$dataset = $new_model->getDataset();
$dataset->name = 'hello world!';

try {
	$id = $new_model->insert($dataset);
}
catch(InsertException $e) {
	$id = 0;
}

// Update a row in the model
$dataset = $new_model->getDataset($id);
$dataset->name = 'Hola Mundo!';

try {
	$new_model->update($id, $dataset);
}
catch (UpdateException $e) {}

try {
	$data = $new_model->getData($id);
}
catch(SelectException $e) {
	$data = $new_model->getData(EMTPY_DATA);
}
echo $data->name.'<br/>';

// Delete all values
try {
	$new_model->delete(ALL_ROWS);
}
catch(DeleteException $e) {}
