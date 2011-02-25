<?php

if(!defined("e107_INIT")) {
	require_once("../../class2.php");
}
require_once(e_HANDLER."userclass_class.php");
if(!getperms("P")){ header("location:".e_BASE."index.php"); exit;}
require_once(e_ADMIN."auth.php");

	
if(isset($_POST['updatesettings'])){
	$pref['wowapp_guildname'] = $tp->toDB($_POST['wowapp_guildname']);
	$pref['wowapp_rules'] = $tp->toDB($_POST['wowapp_rules']);
	$pref['wowapp_rulesrequired'] = $tp->toDB($_POST['wowapp_rulesrequired']);
	$pref['wowapp_viewaccess'] = $_POST['wowapp_viewaccess'];
	$pref['wowapp_rankaccess'] = $_POST['wowapp_rankaccess'];
	$pref['wowapp_manageaccess'] = $_POST['wowapp_manageaccess'];
	$pref['wowapp_replymethod'] = $tp->toDB($_POST['wowapp_replymethod']);
	$pref['wowapp_externalallowed'] = $tp->toDB($_POST['wowapp_externalallowed']);
	$pref['wowapp_wowrecruitlink'] = $tp->toDB($_POST['wowapp_wowrecruit']);
	save_prefs();
	$message = "Settings saved successfully!";
}

if (isset($message)) {
	$ns->tablerender("", "<div style='text-align:center'><b>".$message."</b></div>");
}

$text = "
<div style='text-align:center'>
<form method='post' action='".e_SELF."'>
<table style='width:75%' class='fborder'>
<tr>
<td style='width:50%' class='forumheader3'>What is your guild's name?</td>
<td style='width:50%; text-align:right' class='forumheader3'>
<input type='text' name='wowapp_guildname' class='tbox' value='".$pref['wowapp_guildname']."' />
</td>
</tr>
<tr>
<td style='width:50%' class='forumheader3'>Who can view applications?</td>
<td style='width:50%; text-align:right' class='forumheader3'>
".r_userclass('wowapp_viewaccess', $pref['wowapp_viewaccess'], 'off', 'nobody,member,admin,classes')."
</td>
</tr>
<tr>
<td style='width:50%' class='forumheader3'>Who can rank and comment on applications?</td>
<td style='width:50%; text-align:right' class='forumheader3'>
".r_userclass('wowapp_rankaccess', $pref['wowapp_rankaccess'], 'off', 'nobody,member,admin,classes')."
</td>
</tr>
<tr>
<td style='width:50%' class='forumheader3'>Who can manage applications?</td>
<td style='width:50%; text-align:right' class='forumheader3'>
".r_userclass('wowapp_manageaccess', $pref['wowapp_manageaccess'], 'off', 'nobody,member,admin,classes')."
</td>
</tr>
<tr>
<td style='width:50%' class='forumheader3'>Reply method used when contact applicants regarding their acceptance or denial:</td>
<td style='width:50%; text-align:right' class='forumheader3'>
<select name='wowapp_replymethod' class='tbox'>
<option value='pm'".($pref['wowapp_replymethod'] == "pm" ? " selected" : "").">PM</option>
<option value='email'".($pref['wowapp_replymethod'] == "email" ? " selected" : "").">Email</option>
</select>
</td>
</tr>
<tr>
<td style='width:50%' class='forumheader3'>Allow external realm applicants?</td>
<td style='width:50%; text-align:right' class='forumheader3'>
<input type='checkbox' name='wowapp_externalallowed' value='1'".($pref['wowapp_externalallowed'] == 1 ? " checked='checked'" : "")." />
</td>
</tr>
<tr>
<td style='width:50%' class='forumheader3'>Link with the WoW Recruitment Menu?</td>
<td style='width:50%; text-align:right' class='forumheader3'>
<input type='checkbox' name='wowapp_wowrecruit' value='1'".($pref['wowapp_wowrecruitlink'] == 1 ? " checked='checked'" : "")." />
</td>
</tr>
<tr>
<td style='width:50%' class='forumheader3'>Require applicants to agree to your guild rules before their application is submitted?</td>
<td style='width:50%; text-align:right' class='forumheader3'>
<input type='checkbox' name='wowapp_rulesrequired' value='1'".($pref['wowapp_rulesrequired'] == 1 ? " checked='checked'" : "")." />
</td>
</tr>
<tr>
<td style='width:50%' class='forumheader3'>Guild Rules:</td>
<td style='width:50%; text-align:right' class='forumheader3'>
<textarea class='tbox' name='wowapp_rules' style='width:90%; height:50px;'>".$pref['wowapp_rules']."</textarea>
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

$ns->tablerender("Configure WoW Guild Application", $text);
require_once(e_ADMIN."footer.php");
?>
