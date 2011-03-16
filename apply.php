<?php
require_once("../../class2.php");
require_once(HEADERF);
include_once(e_HANDLER."secure_img_handler.php");

global $captcha;
$captcha = new secure_image;
$sql3 = new db();
$sql4 = new db();

if(check_class($pref['avalanche_applyaccess'])){

	$ta1 = $sql4->db_Count("avalanche_request", "(*)", "WHERE av_uid='".intval(USERID)."'");
	$ta2 = $sql4->db_Count("avalanche_application", "(*)");
	$times_applied = $ta1 / $ta2;

	if($times_applied < $pref['avalanche_applyamount']){

		$sql3->db_Select("avalanche_request", "*", "ORDER BY av_aid DESC LIMIT 1", "no-where");
		while($row3 = $sql3->db_Fetch()){
			$app_id = $row3['av_aid'] + 1;
		}
		$app_id = ($app_id == 0 ? 1 : $app_id);

		if(isset($_POST['apply'])){
			$fields = array();
			$types = array();
			$required = array();
			$sql2->db_Select("avalanche_application", "*");
			while($row2 = $sql2->db_Fetch()){
				array_push($fields, $row2['av_fieldname']);
				array_push($types, $row2['av_type']);
				array_push($required, $row2['av_required']);
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

			$tenfour_rules = ($pref['avalanche_rulesrequired'] == true && $pref['avalanche_rules'] != "" ? ($_POST['acceptrules'] == true ? true : false) : true);

			if($tenfour_rules){
				if($proceed){
					if($captcha->verify_code($_POST['rand_num'], $_POST['code_verify'])){
						for($i = 0; $i <= (count($fields)-1); $i++){
							if($types[$i] == "checkbox"){
								$cbv = $_POST[$fields[$i]];
								for($x = 0; $x < count($cbv); $x++){
									$chkvalues .= $cbv[$x]."//";
								}
								$sql3->db_Insert("avalanche_request", "'', '".intval(USERID)."', '".intval($i+1)."', '".intval($app_id)."', '".$chkvalues."', '".intval(time())."', '0'");
							}else{
								$sql3->db_Insert("avalanche_request", "'', '".intval(USERID)."', '".intval($i+1)."', '".intval($app_id)."', '".$tp->toDB($_POST[$fields[$i]])."', '".intval(time())."', '0'");
							}
						}
						$message = "Your application has been submitted successfully.<br />
						You will be contacted when a decision has been made.<br />
						You can further comment on your application by visiting <a href='".e_PLUGIN."avalanche/discuss.php?id.".$app_id."'>this page</a>.";
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


		$sql->db_Select("avalanche_application", "*", "ORDER BY av_id ASC", "no-where");

		$text = "<div style='text-align:center;'>
		<form method='post' action='".e_SELF."'>
		<table style='width:90%; padding 4px;' class='fborder'>";

		if($pref['avalanche_rulesrequired'] == true && $pref['avalanche_rules'] != ""){
			$text .= "<tr>
			<td colspan='2' class='forumheader3'>
			".$tp->toHTML($pref['avalanche_rules'], true)."
			<br /><br />
			<div style='text-align:center;'>
			<b>Do you accept these rules?</b> <input type='checkbox' name='acceptrules' value='1' />
			</div>
			</td>
			</tr>";
		}

		while($row = $sql->db_Fetch()){
			$text .= "<tr>
			<td class='forumheader3' style='width:50%;'>".($row['av_required'] == true ? ($pref['avalanche_requiredfieldtext'] == "" ? "<span style='color: #cc0000;'>*</span> " : $tp->toHTML($pref['avalanche_requiredfieldtext'])) : "").$row['av_key']."</td>
			<td class='forumheader3' style='text-align:right;'>";

			if($row['av_type'] == "textbox"){
				$text .= "<input type='text' class='tbox' name='".$row['av_fieldname']."' value='".$row['av_value']."' />";

			}else if($row['av_type'] == "textarea"){
				$text .= "<textarea class='tbox' name='".$row['av_fieldname']."' style='width:100%; height:50px;'>".$row['av_value']."</textarea>";

			}else if($row['av_type'] == "radio"){
				$values = explode("//", $row['av_value']);
				for($i = 0; $i <= (count($values)-1); $i++){
					$text .= "<input type='radio' name='".$row['av_fieldname']."' value='".$i."' /> ".$values[$i]." ";
				}
				unset($values);

			}else if($row['av_type'] == "checkbox"){
				$values = explode("//", $row['av_value']);
				for($i = 0; $i <= (count($values)-1); $i++){
					$text .= $values[$i]." <input type='checkbox' name='".$row['av_fieldname']."[]' value='".$i."' /><br />";
				}
				unset($values);

			}else if($row['av_type'] == "dropdown"){
				$values = explode("//", $row['av_value']);
				$text .= "<select class='tbox' name='".$row['av_fieldname']."'>";
				for($i = 0; $i <= (count($values)-1); $i++){
					$text .= "<option value='".$i."'>".$values[$i]."</option>";
				}
				$text .= "</select>";
				unset($values);

			}

			$text .= "</td>\n</tr>";
		}

		$text .= "<tr>
		<td colspan='2' style='text-align:center;' class='forumheader3'>
		<input type='hidden' name='rand_num' value='".$captcha->random_number."' />
		".$captcha->r_image()."
		<br />
		<input type='text' class='tbox' name='code_verify' value='' />
		</td>
		</tr>
		<tr>
		<td colspan='2' style='text-align:center;' class='forumheader3'><input type='submit' class='button' name='apply' value='Submit Application' /> <input type='reset' class='button' value='Start Over' /></td>
		</tr>
		<tr>
		<td colspan='2' style='text-align:center;' class='forumheader3'>".($pref['avalanche_requiredfieldtext'] == "" ? "<span style='color: #cc0000;'>*</span> " : $tp->toHTML($pref['avalanche_requiredfieldtext']))." - denotes a required field.</td>
		</tr>
		</table>
		</form>
		</div>";

		$ns->tablerender("Apply to ".$pref['avalanche_groupname'], $text);
	}else{
		$ns->tablerender("Application Limit Met", "You've already submitted the maximum amount of applications allowed.<br />
		Will we contact you when a decision has been made regarding your application.");
	}
}else{
	$ns->tablerender("Access Denied! :D", "<div style='text-align:center;'>You do not have the correct access to view this page.</div>");
}
	
require_once(FOOTERF);
?>