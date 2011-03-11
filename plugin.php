<?php

//PLUGIN INFO------------------------------------------------------------------------------------------------+

$eplug_name        = "Avalanche";
$eplug_version     = "ALPHA";
$eplug_author      = "Patrick Weaver";
$eplug_url         = "http://painswitch.com/";
$eplug_email       = "patrickweaver@gmail.com";
$eplug_description = "Modular Group Application Manager";
$eplug_compatible  = "e107 0.7+";
$eplug_readme      = "";
$eplug_compliant   = TRUE;
$eplug_module      = FALSE;

//PLUGIN FOLDER----------------------------------------------------------------------------------------------+

$eplug_folder     = "avalanche";

//PLUGIN MENU FILE-------------------------------------------------------------------------------------------+

$eplug_menu_name  = "";

//PLUGIN ADMIN AREA FILE-------------------------------------------------------------------------------------+

$eplug_conffile   = "admin_config.php";

//PLUGIN ICONS AND CAPTION-----------------------------------------------------------------------------------+

$eplug_logo       = "";
$eplug_icon       = "";
$eplug_icon_small = "";
$eplug_caption    = "Configure Avalanche";

//DEFAULT PREFERENCES----------------------------------------------------------------------------------------+

$eplug_prefs = array(
	"avalanche_groupname" => "",
	"avalanche_rules" => "",
	"avalanche_rulesrequired" => "0",
	"avalanche_applyaccess" => "",
	"avalanche_viewaccess" => "",
	"avalanche_rankaccess" => "",
	"avalanche_manageaccess" => "",
	"avalanche_replymethod" => "pm",
	"avalanche_requiredfieldtext" => "<span style='color: #cc0000;'>*</span> ",
	"avalanche_applyamount" => "1",
	"avalanche_votecolors" => "#00bf00,#bf0000",
	"avalanche_votecommentediting" => "0"
);

//MYSQL TABLES TO BE CREATED---------------------------------------------------------------------------------+

$eplug_table_names = array("avalanche_application", "avalanche_request", "avalanche_comment", "avalanche_discuss");

//MYSQL TABLE STRUCTURE--------------------------------------------------------------------------------------+

$eplug_tables = array(
	"CREATE TABLE ".MPREFIX."avalanche_application (
		av_id int(10) unsigned NOT NULL auto_increment,
		av_key varchar(250) NOT NULL,
		av_fieldname varchar(250) NOT NULL,
		av_type varchar(250) NOT NULL,
		av_value text NOT NULL,
		av_required tinyint(3) unsigned NOT NULL,
		PRIMARY KEY  (av_id)
	) TYPE=MyISAM AUTO_INCREMENT=1;",

	"CREATE TABLE ".MPREFIX."avalanche_request (
		av_id int(10) unsigned NOT NULL auto_increment,
		av_uid int(10) unsigned NOT NULL,
		av_qid int(10) unsigned NOT NULL,
		av_aid int(10) unsigned NOT NULL,
		av_value text NOT NULL,
		av_datestamp int(10) unsigned NOT NULL,
		PRIMARY KEY  (av_id)
	) TYPE=MyISAM AUTO_INCREMENT=1;",

	"CREATE TABLE ".MPREFIX."avalanche_comment (
		av_id int(10) unsigned NOT NULL auto_increment,
		av_uid int(10) unsigned NOT NULL,
		av_aid int(10) unsigned NOT NULL,
		av_comment text NOT NULL,
		av_vote int(10) unsigned NOT NULL,
		av_datestamp int(10) unsigned NOT NULL,
		PRIMARY KEY  (av_id)
	) TYPE=MyISAM AUTO_INCREMENT=1;",

	"CREATE TABLE ".MPREFIX."avalanche_discuss (
		av_id int(10) unsigned NOT NULL auto_increment,
		av_aid int(10) unsigned NOT NULL,
		av_uid int(10) unsigned NOT NULL,
		av_comment text NOT NULL,
		av_datestamp int(10) unsigned NOT NULL,
		PRIMARY KEY  (av_id)
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