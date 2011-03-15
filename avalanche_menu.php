<?php

if (!defined('e107_INIT')) { exit; }

// THIS MENU ITEM IS NOT THE FINAL VERSION
// IT IS MERELY BEING COMMITTED SO THAT PEOPLE TESTING THIS PLUGIN HAVE EASY ACCESS TO THE VARIOUS PAGES

$text = "The following pages are available to you:<br /><br />
<ul>";
if(check_class($pref['avalanche_applyaccess'])){
	$text .= "<li><a href='".e_PLUGIN."avalanche/apply.php'>apply.php</a></li>";
}
if(check_class($pref['avalanche_viewaccess'])){
	$text .= "<li><a href='".e_PLUGIN."avalanche/archive.php'>archive.php</a></li>
	<li><a href='".e_PLUGIN."avalanche/review.php'>review.php</a></li>";
}
$text .="</ul>";

$ns->tablerender("Avalanche Management", $text, 'avalanche');

?>