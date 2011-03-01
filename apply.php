<?php
require_once("../../class2.php");
require_once(HEADERF);
include_once(e_HANDLER."secure_img_handler.php");

global $captcha;
$captcha = new secure_image;
$sql3 = new db();

if(USER){
	if(isset($_POST['apply'])){
		$fields = array();
		$types = array();
		$required = array();
		$sql2->db_Select("wowapp_application", "*");
		while($row2 = $sql2->db_Fetch()){
			array_push($fields, $row2['wa_fieldname']);
			array_push($types, $row2['wa_type']);
			array_push($required, $row2['wa_required']);
		}

		for($y = 0; $y <= (count($fields)-1); $y++){
			if($_POST[$fields[$y]] == ""){
				if($required[$y] == true){
					$proceed = false;
					break;
				}else{
					$proceed = true;
				}
			}else{
				$proceed = true;
			}
		}

		if($pref['wowapp_rulesrequired'] == true && $pref['wowapp_rules'] != ""){
			if($_POST['acceptrules'] == true){
				$tenfour_rules = true;
			}else{
				$tenfour_rules = false;
			}
		}else{
			$tenfour_rules = true;
		}
		
		if($tenfour_rules){
			if($proceed){
				if($captcha->verify_code($_POST['rand_num'], $_POST['code_verify'])){
					for($i = 0; $i <= (count($fields)-1); $i++){
						if($types[$i] == "checkbox"){
							$cbv = $_POST[$fields[$i]];
							for($x = 0; $x < count($cbv); $x++){
								$chkvalues .= $cbv[$x].",";
							}
							$sql3->db_Insert("wowapp_request", "'', '".intval(USERID)."', '".intval($i+1)."', '".$chkvalues."'") or die(mysql_error());
						}else{
							$sql3->db_Insert("wowapp_request", "'', '".intval(USERID)."', '".intval($i+1)."', '".$tp->toDB($_POST[$fields[$i]])."'") or die(mysql_error());
						}
					}
					$message = "Your application has been submitted successfully.<br />You will be contacted when a decision has been made.";
				}else{
					$message = "Security code is incorrect!";
				}
			}else{
				$message = "Please fill in all required fields to submit your application.";
			}
		}else{
			$message = "You need to accept our rules and regulations before you can proceed.";
		}
	}

	if(isset($message)){
		$ns->tablerender("", "<div style='text-align:center'><b>".$message."</b></div>");
	}

	$sql->db_Select("wowapp_application", "*");

	$text = "<div style='text-align:center;'>
	<form method='post' action='".e_SELF."'>
	<table style='width:90%; padding 4px;' class='fborder'>";

	if($pref['wowapp_rulesrequired'] == true && $pref['wowapp_rules'] != ""){
		$text .= "<tr>
		<td colspan='2' style='text-align:justify;'>
		".$tp->toHTML($pref['wowapp_rules'], true)."
		<br /><br />
		<div style='text-align:center;'>
		Do you accept these rules? <input type='checkbox' name='acceptrules' value='1' />
		</div>
		</td>
		</tr>";
	}

	$text .= "<tr>
	<td style='width:50%; text-align:left;'>&nbsp;</td>
	<td>&nbsp;</td>
	</tr>";

	while($row = $sql->db_Fetch()){
		$text .= "<tr>
		<td style='text-align:left;'>".($row['wa_required'] == true ? ($pref['wowapp_requiredfieldtext'] == "" ? "<span style='color: #cc0000;'>*</span> " : $tp->toHTML($pref['wowapp_requiredfieldtext'])) : "").$row['wa_key']."</td>
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
	<td colspan='2'>
	<input type='hidden' name='rand_num' value='".$captcha->random_number."' />
	".$captcha->r_image()."
	<br />
	<input type='text' class='tbox' name='code_verify' value='' />
	</td>
	</tr>
	<tr>
	<td colspan='2'><input type='submit' class='button' name='apply' value='Submit Application' /> <input type='reset' class='button' value='Start Over' /></td>
	</tr>
	<tr>
	<td colspan='2'>".($pref['wowapp_requiredfieldtext'] == "" ? "<span style='color: #cc0000;'>*</span> " : $tp->toHTML($pref['wowapp_requiredfieldtext']))." - denotes a required field.</td>
	</tr>
	</table>
	</form>
	</div>";

	$ns->tablerender("Apply to ".$pref['wowapp_guildname'], $text);
}else{
	$ns->tablerender("Access Denied! :D", "You do not have the correct access to view this page.");
}
	
require_once(FOOTERF);
?>