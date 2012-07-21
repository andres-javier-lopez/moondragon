<?php

require_once '../moondragon.caller.php';

$call = new Caller('http://localhost/moondragon4.0/test/test.json');
$data = Json::decode($call->get());

echo $data->message.'<br/>';
echo $data->words[0]->word.'<br/>';
echo $data->words[1]->word.'<br/>';
