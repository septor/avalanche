<?php
if (!defined('e107_INIT')) { exit; }

define("NEWIMG", "<img src='".(file_exists(THEME."images/avalanche/new.png") ? THEME."images/avalanche/new.png" : e_PLUGIN."avalanche/images/new.png")."' title='This application is new to you!' />");
define("DELETEIMG", "<img src='".(file_exists(THEME."images/avalanche/delete.png") ? THEME."images/avalanche/delete.png" : e_PLUGIN."avalanche/images/delete.png")."' title='Delete this application!' />");
define("ACCEPTIMG", "<img src='".(file_exists(THEME."images/avalanche/accept.png") ? THEME."images/avalanche/accept.png" : e_PLUGIN."avalanche/images/accept.png")."' title='Accept this application!' />");
define("DENYIMG", "<img src='".(file_exists(THEME."images/avalanche/deny.png") ? THEME."images/avalanche/deny.png" : e_PLUGIN."avalanche/images/deny.png")."' title='Deny this application!' />");
define("EDITIMG", "<img src='".(file_exists(THEME."images/avalanche/edit.png") ? THEME."images/avalanche/edit.png" : e_PLUGIN."avalanche/images/edit.png")."' title='Edit your comment!' />");
define("YESIMG", "<img src='".(file_exists(THEME."images/avalanche/yes.png") ? THEME."images/avalanche/yes.png" : e_PLUGIN."avalanche/images/yes.png")."' title='Yes!' />");
define("NOIMG", "<img src='".(file_exists(THEME."images/avalanche/no.png") ? THEME."images/avalanche/no.png" : e_PLUGIN."avalanche/images/no.png")."' title='No!' />");

?>