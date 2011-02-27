<?php

//PLUGIN INFO------------------------------------------------------------------------------------------------+

$eplug_name        = "WoW Guild Application";
$eplug_version     = "BETA";
$eplug_author      = "Patrick Weaver";
$eplug_url         = "http://painswitch.com/";
$eplug_email       = "patrickweaver@gmail.com";
$eplug_description = "World of Warcraft Guild Application";
$eplug_compatible  = "e107 0.7+";
$eplug_readme      = "";
$eplug_compliant   = TRUE;
$eplug_module      = FALSE;

//PLUGIN FOLDER----------------------------------------------------------------------------------------------+

$eplug_folder     = "wowapp";

//PLUGIN MENU FILE-------------------------------------------------------------------------------------------+

$eplug_menu_name  = "";

//PLUGIN ADMIN AREA FILE-------------------------------------------------------------------------------------+

$eplug_conffile   = "admin_config.php";

//PLUGIN ICONS AND CAPTION-----------------------------------------------------------------------------------+

$eplug_logo       = "";
$eplug_icon       = "";
$eplug_icon_small = "";
$eplug_caption    = "Configure WoW Guild Application";

//DEFAULT PREFERENCES----------------------------------------------------------------------------------------+

$eplug_prefs = array(
	"wowapp_guildname" => "",
	"wowapp_rules" => "",
	"wowapp_rulesrequired" => "1",
	"wowapp_viewaccess" => "",
	"wowapp_rankaccess" => "",
	"wowapp_manageaccess" => "",
	"wowapp_externalallowed" => "1",
	"wowapp_replymethod" => "pm",
	"wowapp_wowrecruitlink" => "0"
);

//MYSQL TABLES TO BE CREATED---------------------------------------------------------------------------------+

$eplug_table_names = array("wowapp_application", "wowapp_request", "wowapp_comment", "wowapp_armory");

//MYSQL TABLE STRUCTURE--------------------------------------------------------------------------------------+

$eplug_tables = array(
	"CREATE TABLE ".MPREFIX."wowapp_application (
		wa_id int(10) unsigned NOT NULL auto_increment,
		wa_key varchar(250) NOT NULL,
		wa_fieldname varchar(250) NOT NULL,
		wa_type varchar(250) NOT NULL,
		wa_value text NOT NULL,
		PRIMARY KEY  (wa_id)
	) TYPE=MyISAM AUTO_INCREMENT=1;",

	"CREATE TABLE ".MPREFIX."wowapp_request (
		wa_id int(10) unsigned NOT NULL auto_increment,
		wa_uid int(10) unsigned NOT NULL,
		wa_qid int(10) unsigned NOT NULL,
		wa_value text NOT NULL,
		PRIMARY KEY  (wa_id)
	) TYPE=MyISAM AUTO_INCREMENT=1;",

	"CREATE TABLE ".MPREFIX."wowapp_comment (
		wa_id int(10) unsigned NOT NULL auto_increment,
		wa_uid int(10) unsigned NOT NULL,
		wa_aid int(10) unsigned NOT NULL,
		wa_comment text NOT NULL,
		wa_vote int(10) unsigned NOT NULL default '2',
		PRIMARY KEY  (wa_id)
	) TYPE=MyISAM AUTO_INCREMENT=1;",

	"CREATE TABLE ".MPREFIX."wowapp_armory (
		wa_id int(10) unsigned NOT NULL auto_increment,
		wa_class varchar(250) NOT NULL,
		wa_spec varchar(250) NOT NULL,
		wa_gather varchar(250) NOT NULL,
		PRIMARY KEY  (wa_id)
	) TYPE=MyISAM AUTO_INCREMENT=1;"
);

//LINK TO BE CREATED ON SITE MENU--------------------------------------------------------------------------+

$eplug_link      = FALSE;
$eplug_link_name = "";
$eplug_link_url  = "";

//MESSAGE WHEN PLUGIN IS INSTALLED-------------------------------------------------------------------------+

$eplug_done = $eplug_name." has been sucessfully installed!";

//SAME AS ABOVE BUT ONLY RUN WHEN CHOOSING UPGRADE---------------------------------------------------------+

$upgrade_add_prefs    = "";
$upgrade_remove_prefs = "";
$upgrade_alter_tables = "";
$eplug_upgrade_done   = "";

//---------------------------------------------------------------------------------------------------------+

?>