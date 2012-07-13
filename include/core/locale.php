<?php

$locale = isset($_GET['lang'])?$_GET['lang']:'es_SV';

putenv("LANG=$locale.utf-8");
setlocale(LC_ALL, "$locale.utf-8");

$dom = bindtextdomain("messages", realpath(MOONDRAGON_PATH."/locale"));
textdomain("messages");

assert('textdomain(NULL) == "messages"');
assert('$dom == realpath(MOONDRAGON_PATH."/locale")');
