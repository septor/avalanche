<?php

if(!defined("e107_INIT")) {
	require_once("../../class2.php");
}
require_once(e_HANDLER."userclass_class.php");
if(!getperms("P")){ header("location:".e_BASE."index.php"); exit;}
require_once(e_ADMIN."auth.php");

	
if(isset($_POST['updatesettings'])){
	if($_POST['avalanche_rulesrequired'] == true && $_POST['avalanche_rules'] == ""){
		$message = "You're making applicants agree to rules you haven't even set? That's kind of dodgy! Make some rules, jerk!";
	}else if($_POST['avalanche_voteyes'] == "" || $_POST['avalanche_voteno'] == ""){
		$message = "You must choose two vote colors.";
	}else{
		$pref['avalanche_groupname'] = $tp->toDB($_POST['avalanche_groupname']);
		$pref['avalanche_rules'] = $tp->toDB($_POST['avalanche_rules']);
		$pref['avalanche_rulesrequired'] = $tp->toDB($_POST['avalanche_rulesrequired']);
		$pref['avalanche_viewaccess'] = $_POST['avalanche_viewaccess'];
		$pref['avalanche_rankaccess'] = $_POST['avalanche_rankaccess'];
		$pref['avalanche_manageaccess'] = $_POST['avalanche_manageaccess'];
		$pref['avalanche_applyaccess'] = $_POST['avalanche_applyaccess'];
		$pref['avalanche_requiredfieldtext'] = $tp->toDB($_POST['avalanche_requiredfieldtext']);
		$pref['avalanche_applyamount'] = $tp->toDB($_POST['avalanche_applyamount']);
		$pref['avalanche_votecolors'] = $tp->toDB($_POST['avalanche_voteyes'].",".$_POST['avalanche_voteno']);
		$pref['avalanche_votecommentediting'] = $tp->toDB($_POST['avalanche_votecommentediting']);
		$pref['avalanche_acceptsubject'] = $tp->toDB($_POST['avalanche_acceptsubject']);
		$pref['avalanche_acceptmessage'] = $tp->toDB($_POST['avalanche_acceptmessage']);
		$pref['avalanche_denysubject'] = $tp->toDB($_POST['avalanche_denysubject']);
		$pref['avalanche_denymessage'] = $tp->toDB($_POST['avalanche_denymessage']);
		save_prefs();
		$message = "Settings saved successfully!";
	}
}

if (isset($message)) {
	$ns->tablerender("", "<div style='text-align:center'><b>".$message."</b></div>");
}

$votecolor = explode(",", $pref['avalanche_votecolors']);

$text = "
<div style='text-align:center'>
<form method='post' action='".e_SELF."'>
<table style='width:75%' class='fborder'>
<tr>
<td style='width:50%' class='forumheader3'>What is your group's name?</td>
<td style='width:50%; text-align:right' class='forumheader3'>
<input type='text' name='avalanche_groupname' class='tbox' value='".$pref['avalanche_groupname']."' />
</td>
</tr>
<tr>
<td style='width:50%' class='forumheader3'>Rules & Regulations:</td>
<td style='width:50%; text-align:center' class='forumheader3'>
<textarea class='tbox' name='avalanche_rules' style='width:90%; height:80px;'>".$pref['avalanche_rules']."</textarea><br />
Force applicants to agree to these rules?: <input type='checkbox' name='avalanche_rulesrequired' value='1'".($pref['avalanche_rulesrequired'] == 1 ? " checked='checked'" : "")." />
</td>
</tr>
<tr>
<td style='width:50%' class='forumheader3'>Amount of times a user can submit an application?</td>
<td style='width:50%; text-align:right' class='forumheader3'>
<input type='text' name='avalanche_applyamount' class='tbox' value='".$pref['avalanche_applyamount']."' />
</td>
</tr>
<tr>
<td style='width:50%' class='forumheader3'>Who can submit applications?</td>
<td style='width:50%; text-align:right' class='forumheader3'>
".r_userclass('avalanche_applyaccess', $pref['avalanche_applyaccess'], 'off', 'nobody,member,admin,main,classes')."
</td>
</tr>
<tr>
<td style='width:50%' class='forumheader3'>Who can view applications?</td>
<td style='width:50%; text-align:right' class='forumheader3'>
".r_userclass('avalanche_viewaccess', $pref['avalanche_viewaccess'], 'off', 'nobody,member,admin,main,classes')."
</td>
</tr>
<tr>
<td style='width:50%' class='forumheader3'>Who can vote and comment on applications?</td>
<td style='width:50%; text-align:right' class='forumheader3'>
".r_userclass('avalanche_rankaccess', $pref['avalanche_rankaccess'], 'off', 'nobody,member,admin,main,classes')."
</td>
</tr>
<tr>
<td style='width:50%' class='forumheader3'>Who can approve, deny, and delete applications?</td>
<td style='width:50%; text-align:right' class='forumheader3'>
".r_userclass('avalanche_manageaccess', $pref['avalanche_manageaccess'], 'off', 'nobody,member,admin,main,classes')."
</td>
</tr>
<tr>
<td style='width:50%' class='forumheader3'>Vote Colors:<br /><i>HTML color codes only.</i></td>
<td style='width:50%; text-align:right' class='forumheader3'>
Yes: <input type='text' name='avalanche_voteyes' class='tbox' value='".$votecolor[0]."' /><br /><br />
No: <input type='text' name='avalanche_voteno' class='tbox' value='".$votecolor[1]."' />
</td>
</tr>
<tr>
<td style='width:50%' class='forumheader3'>Allow users to edit their comments after they vote?:</td>
<td style='width:50%; text-align:right' class='forumheader3'>
<input type='checkbox' name='avalanche_votecommentediting' value='1'".($pref['avalanche_votecommentediting'] == 1 ? " checked='checked'" : "")." />
</td>
</tr>
<tr>
<td style='width:50%' class='forumheader3'>Character, image, or text to place before a requied field on the application:<br /><i>Image tags are <b>not</b> inserted!</i></td>
<td style='width:50%; text-align:right' class='forumheader3'>
<input type='text' name='avalanche_requiredfieldtext' class='tbox' value='".$pref['avalanche_requiredfieldtext']."' />
</td>
</tr>
<tr>
<td style='width:50%' class='forumheader3'>Application accepted message:<br /><i>{GROUPNAME} will be replaced with your group's name.</i></td>
<td style='width:50%; text-align:right' class='forumheader3'>
<table style='width:100%;'>
<tr>
<td style='width:20%; text-align:right;'>Subject:</td>
<td><input type='text' name='avalanche_acceptsubject' style='width:91%;' class='tbox' value='".$pref['avalanche_acceptsubject']."' /></td>
</tr>
<tr>
<td style='width:20%; text-align:right;'>Body:</td>
<td><textarea class='tbox' name='avalanche_acceptmessage' style='width:90%; height:80px;'>".$pref['avalanche_acceptmessage']."</textarea></td>
</tr>
</table>
</td>
</tr>
<tr>
<td style='width:50%' class='forumheader3'>Application denied message:<br /><i>{GROUPNAME} will be replaced with your group's name.</i></td>
<td style='width:50%; text-align:right' class='forumheader3'>
<table style='width:100%;'>
<tr>
<td style='width:20%; text-align:right;'>Subject:</td>
<td><input type='text' name='avalanche_denysubject' style='width:91%;' class='tbox' value='".$pref['avalanche_denysubject']."' /></td>
</tr>
<tr>
<td style='width:20%; text-align:right;'>Body:</td>
<td><textarea class='tbox' name='avalanche_denymessage' style='width:90%; height:80px;'>".$pref['avalanche_denymessage']."</textarea></td>
</tr>
</table>
</td>
</tr>
<tr>
<td colspan='2' style='text-align:center' class='forumheader'>
<input class='button' type='submit' name='updatesettings' value='Save Settings' />
</td>
</tr>
</table>
</form>
</div>
";

$ns->tablerender("Configure Avalanche", $text);
require_once(e_ADMIN."footer.php");
?>
