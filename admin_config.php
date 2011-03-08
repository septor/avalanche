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
	}else{
		$pref['avalanche_groupname'] = $tp->toDB($_POST['avalanche_groupname']);
		$pref['avalanche_rules'] = $tp->toDB($_POST['avalanche_rules']);
		$pref['avalanche_rulesrequired'] = $tp->toDB($_POST['avalanche_rulesrequired']);
		$pref['avalanche_viewaccess'] = $_POST['avalanche_viewaccess'];
		$pref['avalanche_rankaccess'] = $_POST['avalanche_rankaccess'];
		$pref['avalanche_manageaccess'] = $_POST['avalanche_manageaccess'];
		$pref['avalanche_applyaccess'] = $_POST['avalanche_applyaccess'];
		$pref['avalanche_replymethod'] = $tp->toDB($_POST['avalanche_replymethod']);
		$pref['avalanche_requiredfieldtext'] = $tp->toDB($_POST['avalanche_requiredfieldtext']);
		$pref['avalanche_applyamount'] = $tp->toDB($_POST['avalanche_applyamount']);
		save_prefs();
		$message = "Settings saved successfully!";
	}
}

if (isset($message)) {
	$ns->tablerender("", "<div style='text-align:center'><b>".$message."</b></div>");
}

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
<td style='width:50%' class='forumheader3'>Amount of times a user can submit an application?</td>
<td style='width:50%; text-align:right' class='forumheader3'>
<input type='text' name='avalanche_applyamount' class='tbox' value='".$pref['avalanche_applyamount']."' />
</td>
</tr>
<tr>
<td style='width:50%' class='forumheader3'>Who can submit applications?</td>
<td style='width:50%; text-align:right' class='forumheader3'>
".r_userclass('avalanche_applyaccess', $pref['avalanche_applyaccess'], 'off', 'nobody,member,admin,classes')."
</td>
</tr>
<tr>
<td style='width:50%' class='forumheader3'>Who can view applications?</td>
<td style='width:50%; text-align:right' class='forumheader3'>
".r_userclass('avalanche_viewaccess', $pref['avalanche_viewaccess'], 'off', 'nobody,member,admin,classes')."
</td>
</tr>
<tr>
<td style='width:50%' class='forumheader3'>Who can rank and comment on applications?</td>
<td style='width:50%; text-align:right' class='forumheader3'>
".r_userclass('avalanche_rankaccess', $pref['avalanche_rankaccess'], 'off', 'nobody,member,admin,classes')."
</td>
</tr>
<tr>
<td style='width:50%' class='forumheader3'>Who can manage applications?</td>
<td style='width:50%; text-align:right' class='forumheader3'>
".r_userclass('avalanche_manageaccess', $pref['avalanche_manageaccess'], 'off', 'nobody,member,admin,classes')."
</td>
</tr>
<tr>
<td style='width:50%' class='forumheader3'>Reply method used when contacting applicants regarding their acceptance or denial:</td>
<td style='width:50%; text-align:right' class='forumheader3'>
<select name='avalanche_replymethod' class='tbox'>
<option value='pm'".($pref['avalanche_replymethod'] == "pm" ? " selected" : "").">PM</option>
<option value='email'".($pref['avalanche_replymethod'] == "email" ? " selected" : "").">Email</option>
</select>
</td>
</tr>
<tr>
<td style='width:50%' class='forumheader3'>Character, image, or text to place before a requied field on the application:<br /><i>Image tags are <b>not</b> inserted!</i></td>
<td style='width:50%; text-align:right' class='forumheader3'>
<input type='text' name='avalanche_requiredfieldtext' class='tbox' value='".$pref['avalanche_requiredfieldtext']."' />
</td>
</tr>
<tr>
<td style='width:50%' class='forumheader3'>Require applicants to agree to your group's rules before their application is submitted?</td>
<td style='width:50%; text-align:right' class='forumheader3'>
<input type='checkbox' name='avalanche_rulesrequired' value='1'".($pref['avalanche_rulesrequired'] == 1 ? " checked='checked'" : "")." />
</td>
</tr>
<tr>
<td style='width:50%' class='forumheader3'>Rules:</td>
<td style='width:50%; text-align:right' class='forumheader3'>
<textarea class='tbox' name='avalanche_rules' style='width:90%; height:50px;'>".$pref['avalanche_rules']."</textarea>
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
