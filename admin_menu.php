<?php

$menutitle  = "Avalanche Navigation";

$butname[]  = "Configuration";
$butlink[]  = "admin_config.php";
$butid[]    = "config";

$butname[]  = "Application Setup";
$butlink[]  = "admin_app.php";
$butid[]    = "application";

global $pageid;
for ($i=0; $i<count($butname); $i++) {
	$var[$butid[$i]]['text'] = $butname[$i];
	$var[$butid[$i]]['link'] = $butlink[$i];
};

show_admin_menu($menutitle, $pageid, $var);

?>
