<?php
require_once("../../class2.php");
require_once(HEADERF);

$sql3 = new db();

if(isset($_POST['apply'])){
	$fields = array();
	$types = array();
	$sql->db_Select("wowapp_application", "*");
	while($row = $sql->db_Fetch()){
		array_push($fields, $row['wa_fieldname']);
		array_push($types, $row['wa_type']);
	}
	foreach($fields as $field){
		if(isset($_POST[$field])){ $proceed = TRUE; }
	}

	if($proceed){
		for($i = 0; $i <= (count($fields)-1); $i++){
			if($types[$i] == "checkbox"){
				$cbv = $_POST[$fields[$i]];
				for($x=0; $x < count($cbv); $x++){
					$chkvalues .= $cbv[$x].",";
				}
				$sql3->db_Insert("wowapp_request", "'', '".intval(USERID)."', '".intval($i+1)."', '".$chkvalues."'") or die(mysql_error());
			}else{
				$sql3->db_Insert("wowapp_request", "'', '".intval(USERID)."', '".intval($i+1)."', '".$_POST[$fields[$i]]."'") or die(mysql_error());
			}
		}
		$message = "Your application has been submitted successfully. You will be contacted when a descision has been made.";
	}else{
		$message = "All fields are required. Fill them in and resubmit your application.";
	}
}

if(isset($message)){
	$ns->tablerender("", "<div style='text-align:center'><b>".$message."</b></div>");
}

$sql->db_Select("wowapp_application", "*");

$text = "<div style='text-align:center;'>
<form method='post' action='".e_SELF."'>
<table style='width:90%; padding 4px;' class='fborder'>
<tr>
<td style='width:40%; text-align:left;'>&nbsp;</td>
<td>&nbsp;</td>
</tr>";

while($row = $sql->db_Fetch()){
	$text .= "<tr>
	<td style='text-align:left;'>".$row['wa_key']."</td>
	<td style='text-align:right;'>";

	if($row['wa_type'] == "textbox"){
		$text .= "<input type='text' class='tbox' name='".$row['wa_fieldname']."' value='".$row['wa_value']."' />";

	}else if($row['wa_type'] == "textarea"){
		$text .= "<textarea class='tbox' name='".$row['wa_fieldname']."' style='width:100%; height:50px;'>".$row['wa_value']."</textarea>";

	}else if($row['wa_type'] == "radio"){
		$values = explode(",", $row['wa_value']);
		for($i = 0; $i <= (count($values)-1); $i++){
			$text .= "<input type='radio' name='".$row['wa_fieldname']."' value='".$i."' /> ".$values[$i]." ";
		}
		unset($values);

	}else if($row['wa_type'] == "checkbox"){
		$values = explode(",", $row['wa_value']);
		for($i = 0; $i <= (count($values)-1); $i++){
			$text .= $values[$i]." <input type='checkbox' name='".$row['wa_fieldname']."[]' value='".$i."' /><br />";
		}
		unset($values);

	}else if($row['wa_type'] == "dropdown"){
		$values = explode(",", $row['wa_value']);
		$text .= "<select class='tbox' name='".$row['wa_fieldname']."'>";
		for($i = 0; $i <= (count($values)-1); $i++){
			$text .= "<option value='".$i."'>".$values[$i]."</option>";
		}
		$text .= "</select>";
		unset($values);

	}

	$text .= "</td>
	</tr>";
}

$text .= "<tr>
<td colspan='2'><input type='submit' class='button' name='apply' value='Submit Application' /> <input type='reset' class='button' value='Start Over' /></td>
</tr>
</table>
</form>
</div>";

$ns->tablerender("Apply to ".$pref['wowapp_guildname'], $text);
	
require_once(FOOTERF);
?>