<?php

if(!defined("e107_INIT")) {
	require_once("../../class2.php");
}
if(!getperms("P")){ header("location:".e_BASE."index.php"); exit;}
require_once(e_ADMIN."auth.php");

if(isset($_POST['create'])){
	if(isset($_POST['key']) && $_POST['fieldname']){
		$sql->db_Insert("avalanche_application", "'', '".$tp->toDB($_POST['key'])."', '".$tp->toDB($_POST['fieldname'])."', '".$_POST['type']."', '".$tp->toDB($_POST['value'])."', '".$tp->toDB($_POST['required'])."'") or die(mysql_error());
		$message = "Your field has been created successfully!";
	}else{
		$message = "You need to define a question and a fieldname in order for your field to be created.";
	}
}

if(e_QUERY){
	$tmp = explode(".", e_QUERY);
	$action = $tmp[0];
	$id = $tmp[1];
	unset($tmp);
}

if(isset($_POST['confirmdelete'])){
	$sql->db_Delete("avalanche_application", "av_id='".intval($_POST['id'])."'");
	$message = "You have successfully deleted field #".$_POST['id']."!";
}
if(isset($_POST['updatefield'])){
	$sql->db_Update("avalanche_application", "av_key='".$tp->toDB($_POST['newkey'])."', av_fieldname='".$tp->toDB($_POST['newfieldname'])."', av_type='".$tp->toDB($_POST['newtype'])."', av_value='".$tp->toDB($_POST['newvalue'])."', av_required='".$tp->toDB($_POST['newrequired'])."'  WHERE av_id='".intval($_POST['id'])."'");
	$message = "Your field has been updated successfully!";
}

if($action == "del"){
	$topcap = "Confirm Delete";
	$toptext = "
	<form method='post' action='".e_SELF."'>
	Really delete field #".$id."?<br />
	<input type='submit' class='button' name='confirmdelete' value='Yes'> <input type='submit' class='button' value='No'>
	<input type='hidden' name='id' value='".$id."'>
	</form>";
}

if($action == "edit"){
	$topcap = "Edit Question";
	$sql2->db_Select("avalanche_application", "*", "av_id='".$id."'");
	while($row2 = $sql2->db_Fetch()){
		$newkey = $row2['av_key'];
		$newfieldname = $row2['av_fieldname'];
		$newtype = $row2['av_type'];
		$newvalue = $row2['av_value'];
		$newrequired = $row2['av_required'];
	}
	$toptext = "
	<form method='post' action='".e_SELF."'>
	<table style='width:40%' class='fborder'>
	<tr>
	<td class='fcaption' colspan='2'>
	Modifying field #".$id."...
	</td>
	</tr>
	<tr>
	<td style='text-align:right;' class='forumheader3'>Question:</td>
	<td class='forumheader3'><input type='text' name='newkey' class='tbox' value='".$newkey."' /></td>
	</tr>
	<tr>
	<td style='text-align:right;' class='forumheader3'>Field Name:</td>
	<td class='forumheader3'><input type='text' name='newfieldname' class='tbox' value='".$newfieldname."' /></td>
	</tr>
	<tr>
	<td style='text-align:right;' class='forumheader3'>Field Type:</td>
	<td class='forumheader3'><select name='newtype' class='tbox'>
	<option value='textbox'".($newtype == "textbox" ? " selected='yes'" : "").">Text Box</option>
	<option value='textarea'".($newtype == "textarea" ? " selected='yes'" : "").">Text Area</option>
	<option value='radio'".($newtype == "radio" ? " selected='yes'" : "").">Radio Button</option>
	<option value='checkbox'".($newtype == "checkbox" ? " selected='yes'" : "").">Checkbox</option>
	<option value='dropdown'".($newtype == "dropdown" ? " selected='yes'" : "").">Drop Down</option>
	</select></td>
	</tr>
	<tr>
	<td style='text-align:right;' class='forumheader3'>Value:</td>
	<td class='forumheader3'><input type='text' name='newvalue' class='tbox' value='".$newvalue."' /></td>
	</tr>
	<td style='text-align:right;' class='forumheader3'>Required?:</td>
	<td class='forumheader3'><input type='checkbox' name='newrequired' value='1' ".($newrequired == true ? " checked" : "")." /></td>
	</tr>
	<tr>
	<td colspan='2' class='forumheader3' style='text-align:center;'>
	<input type='submit' class='button' name='updatefield' value='Confirm Changes'> <input type='submit' class='button' value='Cancel Changes'>
	<input type='hidden' name='id' value='".$id."'>
	</tr>
	</table>
	</form>";
}

if (isset($message)) {
	$ns->tablerender("", "<div style='text-align:center'><b>".$message."</b></div>");
}

if (isset($toptext)) {
	$ns->tablerender($topcap, "<div style='text-align:center'>".$toptext."</div>");
}

$text = "<div style='text-align:center'>
<form method='post' action='".e_SELF."'>
<table style='width:90%' class='fborder'>
	<tr>
		<td class='fcaption'>Question</td>
		<td class='fcaption'>Field Name</td>
		<td class='fcaption'>Field Type</td>
		<td class='fcaption'>Field Values</td>
		<td class='fcaption'>Required</td>
	</tr>
	<tr>
		<td class='forumheader3'>
			<input type='text' name='key' class='tbox' />
		</td>
		<td class='forumheader3'>
			<input type='text' name='fieldname' class='tbox' />
		</td>
		<td class='forumheader3'>
			<select name='type' class='tbox'>
			<option value='textbox'>Text Box</option>
			<option value='textarea'>Text Area</option>
			<option value='radio'>Radio Button</option>
			<option value='checkbox'>Checkbox</option>
			<option value='dropdown'>Drop Down</option>
			</select>
		</td>
		<td class='forumheader3'>
			<input type='text' name='value' class='tbox' />
		</td>
		<td class='forumheader3'>
			<input type='checkbox' name='required' value='1' />
		</td>
	</tr>
	<tr>
		<td class='forumheader2'><i>The question you wish to ask the applicant.</i></td>
		<td class='forumheader2'><i>A name for the field being created. Usually a short word, lower case, without spaces.</i></td>
		<td class='forumheader2'><i>The type of field to use. Simple questions should use text boxes, long answers should use textareas, multipe choice questions should use radio boxes or drop downs, and multiple-select choices should use checkboxes.</i></td>
		<td class='forumheader2'><i>The value(s) inside the field types. If you select radio button, checkbox, or drop down; split your choices with two forward slashes (//).</i></td>
		<td class='forumheader2'><i>Denotes whether or not the field is required.</i></td>
	</tr>
	<tr>
		<td colspan='5' style='text-align:center' class='forumheader'>
			<input class='button' type='submit' name='create' value='Create Field' />
			<input type='reset' class='button' value='Reset' />
		</td>
	</tr>
</table>
</form>
</div>";

$ns->tablerender("Configure Avalanche: Application Information", $text);

$text2 = "<div style='text-align:center'>";

if($sql->db_Count("avalanche_application", "(*)") == 0){
	$text2 .= "No fields have been created at this time.";
}else{
	$sql->db_Select("avalanche_application", "*") or die(mysql_error());
	$text2 .= "
	<table style='width:90%' class='fborder'>
	<tr>
		<td class='fcaption'>ID</td>
		<td class='fcaption'>Question</td>
		<td class='fcaption'>Field Name</td>
		<td class='fcaption'>Field Type</td>
		<td class='fcaption'>Field Values</td>
		<td class='fcaption'>Required</td>
		<td class='fcaption'>&nbsp;</td>
	</tr>";

	while($row = $sql->db_Fetch()){
		$text2 .= "
		<tr>
			<td class='forumheader3'>".$row['av_id']."</td>
			<td class='forumheader3'>".$row['av_key']."</td>
			<td class='forumheader3'>".$row['av_fieldname']."</td>
			<td class='forumheader3'>".$row['av_type']."</td>
			<td class='forumheader3'>".$row['av_value']."</td>
			<td class='forumheader3'>".($row['av_required'] == true ? "Yes" : "No")."</td>
			<td class='forumheader3' style='text-align:center;'><a href='".e_PLUGIN."avalanche/admin_app.php?edit.".$row['av_id']."'>".ADMIN_EDIT_ICON."</a> <a href='".e_PLUGIN."avalanche/admin_app.php?del.".$row['av_id']."'>".ADMIN_DELETE_ICON."</a></td>
		</tr>";
	}

	$text2 .= "</table>";
}

$text2 .= "</div>";

$ns->tablerender("Configure Avalanche: Created Fields", $text2);

require_once(e_ADMIN."footer.php");

?>